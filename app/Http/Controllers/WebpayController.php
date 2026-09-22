<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Services\WebpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\TicketCompradoMail;
use Illuminate\Support\Facades\Mail;

class WebpayController extends Controller
{
    public function iniciar(Venta $venta, WebpayService $webpayService) 
    {

        if ($venta->estado === 'PAGADA') {
            return redirect()
                ->route('ventas.show', $venta);
        }

        try {

            /*
         * Generamos datos únicos para Webpay.
         */
            $buyOrder =
                'TCK-' .
                $venta->id .
                '-' .
                now()->format('His');

            $sessionId =
                'SES-' .
                $venta->id .
                '-' .
                \Illuminate\Support\Str::random(8);


            /*
         * Guardamos los datos antes de enviar
         * la transacción a Transbank.
         */
            $venta->update([
                'webpay_buy_order' => $buyOrder,
                'webpay_session_id' => $sessionId,
            ]);


            $returnUrl =
                route('webpay.retorno');


            Log::info('Iniciando Webpay', [
                'venta_id' => $venta->id,
                'buy_order' => $buyOrder,
                'session_id' => $sessionId,
                'total' => $venta->total,
                'return_url' => $returnUrl,
            ]);


            $response =
                $webpayService
                ->transaction()
                ->create(
                    $buyOrder,
                    $sessionId,
                    $venta->total,
                    $returnUrl
                );


            /*
         * Guardamos token entregado por Webpay.
         */
            $venta->update([
                'webpay_token' =>
                $response->getToken(),
            ]);


            Log::info('Webpay iniciado correctamente', [
                'venta_id' => $venta->id,
                'token' => $response->getToken(),
                'url' => $response->getUrl(),
            ]);


            return view(
                'webpay.redirect',
                [
                    'url' =>
                    $response->getUrl(),

                    'token' =>
                    $response->getToken(),
                ]
            );
        } catch (\Throwable $e) {

            Log::error(
                'Error iniciando Webpay',
                [
                    'venta_id' => $venta->id,
                    'error' => $e->getMessage(),
                    'archivo' => $e->getFile(),
                    'linea' => $e->getLine(),
                ]
            );

            return redirect()
                ->route(
                    'webpay.fallo',
                    $venta
                )
                ->with(
                    'error',
                    'No fue posible iniciar el pago.'
                );
        }
    }


    public function retorno(
        Request $request,
        WebpayService $webpayService
    ) {

        /*
         * Retorno normal desde Webpay.
         */
        $token = $request->input('token_ws');


        /*
         * Si no viene token_ws puede tratarse
         * de una cancelación/abandono.
         */
        if (!$token) {

            return redirect()
                ->route('ventas.create')
                ->with(
                    'error',
                    'El pago fue cancelado o interrumpido.'
                );
        }


        /*
         * Buscamos la venta por el token
         * que guardamos al iniciar Webpay.
         */
        $venta = Venta::where(
            'webpay_token',
            $token
        )->first();


        if (!$venta) {

            return redirect()
                ->route('ventas.create')
                ->with(
                    'error',
                    'No encontramos la venta asociada al pago.'
                );
        }


        /*
         * Evitar confirmar una venta que
         * ya fue procesada.
         */
        if ($venta->estado === 'PAGADA') {

            return redirect()
                ->route(
                    'ventas.show',
                    $venta
                );
        }


        try {

            $response =
                $webpayService
                ->transaction()
                ->commit($token);

            Log::info('CARD DETAIL WEBPAY', [
                'card_detail' => $response->getCardDetail(),
            ]);


            /*
             * Datos entregados por Webpay.
             */

            $responseCode =
                $response->getResponseCode();

            $status =
                $response->getStatus();


            /*
             * Transacción autorizada.
             */
            if (
                $status === 'AUTHORIZED'
                &&
                $responseCode === 0
            ) {

                $cardDetail = $response->getCardDetail();

                $cardNumber = null;

                if (is_array($cardDetail)) {
                    $cardNumber = $cardDetail['card_number'] ?? null;
                }

                $venta->update([

                    'estado' =>
                    'PAGADA',

                    'authorization_code' =>
                    $response
                        ->getAuthorizationCode(),

                    'response_code' =>
                    $responseCode,

                    'payment_type_code' =>
                    $response
                        ->getPaymentTypeCode(),

                    'card_number' =>
                    $cardNumber,

                    'pagada_at' =>
                    now(),

                    'token_ticket' =>
                    (string) \Illuminate\Support\Str::uuid(),
                ]);

                $correoEnviado = false;

                try {

                    Mail::to($venta->correo)
                        ->send(
                            new TicketCompradoMail($venta)
                        );

                    $venta->update([
                        'ticket_enviado_at' => now(),
                    ]);

                    $correoEnviado = true;
                } catch (\Throwable $e) {

                    Log::error(
                        'Error enviando ticket por correo',
                        [
                            'venta_id' => $venta->id,
                            'correo' => $venta->correo,
                            'error' => $e->getMessage(),
                        ]
                    );
                }


                if ($correoEnviado) {

                    return redirect()
                        ->route('ventas.show', $venta)
                        ->with(
                            'success',
                            'Pago realizado correctamente. Hemos enviado tu ticket al correo ' . $venta->correo . '.'
                        );
                }

                return redirect()
                    ->route('ventas.show', $venta)
                    ->with(
                        'warning',
                        'Pago realizado correctamente, pero no fue posible enviar el correo con tu ticket.'
                    );
            }


            /*
             * Pago rechazado.
             */

            $venta->update([

                'estado' =>
                'RECHAZADA',

                'response_code' =>
                $responseCode,

            ]);


            return redirect()
                ->route(
                    'webpay.fallo',
                    $venta
                )
                ->with(
                    'error',
                    'El pago fue rechazado.'
                );
        } catch (\Throwable $e) {

            Log::error(
                'Error confirmando Webpay',
                [
                    'venta_id' =>
                    $venta->id,

                    'token' =>
                    $token,

                    'error' =>
                    $e->getMessage(),
                ]
            );


            return redirect()
                ->route(
                    'webpay.fallo',
                    $venta
                )
                ->with(
                    'error',
                    'No fue posible confirmar el pago.'
                );
        }
    }


    public function fallo(
        ?Venta $venta = null
    ) {

        return view(
            'webpay.fallo',
            compact('venta')
        );
    }
}
