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

        /*
    |--------------------------------------------------------------------------
    | Si ya está pagada, no permitir otro intento
    |--------------------------------------------------------------------------
    */

        if ($venta->estado === 'PAGADA') {

            return redirect()
                ->route('ventas.show', $venta);
        }


        /*
    |--------------------------------------------------------------------------
    | Solo permitir estados que puedan iniciar/reintentar pago
    |--------------------------------------------------------------------------
    */

        if (!in_array(
            $venta->estado,
            [
                'PENDIENTE_PAGO',
                'FALLIDA',
            ]
        )) {

            return redirect()
                ->route(
                    'webpay.fallo',
                    $venta
                )
                ->with(
                    'error',
                    'Esta venta no puede iniciar un nuevo intento de pago.'
                );
        }


        try {

            /*
        |--------------------------------------------------------------------------
        | Preparar venta para un nuevo intento
        |--------------------------------------------------------------------------
        |
        | Si venía FALLIDA, vuelve a PENDIENTE_PAGO.
        | También limpiamos los datos del intento anterior.
        |
        */

            $venta->update([

                'estado' =>
                'PENDIENTE_PAGO',

                'webpay_token' =>
                null,

                'webpay_authorization_code' =>
                null,

                'webpay_response_code' =>
                null,

                'webpay_payment_type_code' =>
                null,

                'webpay_card_number' =>
                null,

            ]);


            /*
        |--------------------------------------------------------------------------
        | Generar datos únicos para Webpay
        |--------------------------------------------------------------------------
        */

            $buyOrder =
                'TCK-' .
                $venta->id .
                '-' .
                now()->format('YmdHis');

            $sessionId =
                'SES-' .
                $venta->id .
                '-' .
                \Illuminate\Support\Str::random(8);


            /*
        |--------------------------------------------------------------------------
        | Guardar datos del nuevo intento
        |--------------------------------------------------------------------------
        */

            $venta->update([

                'webpay_buy_order' =>
                $buyOrder,

                'webpay_session_id' =>
                $sessionId,

            ]);


            /*
        |--------------------------------------------------------------------------
        | URL de retorno
        |--------------------------------------------------------------------------
        */

            $returnUrl =
                route('webpay.retorno');


            Log::info(
                'Iniciando Webpay',
                [
                    'venta_id' =>
                    $venta->id,

                    'buy_order' =>
                    $buyOrder,

                    'session_id' =>
                    $sessionId,

                    'total' =>
                    $venta->total,

                    'return_url' =>
                    $returnUrl,
                ]
            );


            /*
        |--------------------------------------------------------------------------
        | Crear transacción en Webpay
        |--------------------------------------------------------------------------
        */

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
        |--------------------------------------------------------------------------
        | Guardar token entregado por Webpay
        |--------------------------------------------------------------------------
        */

            $venta->update([

                'webpay_token' =>
                $response->getToken(),

            ]);


            Log::info(
                'Webpay iniciado correctamente',
                [
                    'venta_id' =>
                    $venta->id,

                    'token' =>
                    $response->getToken(),

                    'url' =>
                    $response->getUrl(),
                ]
            );


            /*
        |--------------------------------------------------------------------------
        | Redirigir a Webpay
        |--------------------------------------------------------------------------
        */

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

                    'venta_id' =>
                    $venta->id,

                    'error' =>
                    $e->getMessage(),

                    'archivo' =>
                    $e->getFile(),

                    'linea' =>
                    $e->getLine(),

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


    public function retorno(Request $request, WebpayService $webpayService)
    {
        $token = $request->input('token_ws');

        if (!$token) {
            return redirect()
                ->route('ventas.create')
                ->with('error', 'El pago fue cancelado o interrumpido.');
        }

        $venta = Venta::where('webpay_token', $token)->first();

        if (!$venta) {
            return redirect()
                ->route('ventas.create')
                ->with('error', 'No encontramos la venta asociada al pago.');
        }

        if ($venta->estado === 'PAGADA') {
            return redirect()->route('ventas.show', $venta);
        }

        $transaccion = $webpayService->transaction();

        /*
     * 1. Intentar confirmar con Webpay.
     */
        try {
            $response = $transaccion->commit($token);
        } catch (\Throwable $e) {

            Log::warning('Webpay: error en commit', [
                'venta_id' => $venta->id,
                'error' => $e->getMessage(),
            ]);

            /*
         * 2. Consultar el estado, sin repetir commit.
         */
            try {
                $response = $transaccion->status($token);

                Log::info('Webpay: recuperación por status', [
                    'venta_id' => $venta->id,
                    'status' => $response->getStatus(),
                ]);
            } catch (\Throwable $consultaError) {

                Log::error('Webpay: no fue posible recuperar estado', [
                    'venta_id' => $venta->id,
                    'error' => $consultaError->getMessage(),
                ]);

                return redirect()
                    ->route('ventas.create')
                    ->with(
                        'error',
                        'No fue posible verificar el resultado de tu pago. '
                            . 'Contacta a soporte con el folio '
                            . $venta->folio
                            . ' antes de intentar pagar nuevamente.'
                    );
            }
        }

        /*
     * 3. Verificar que la respuesta pertenece
     *    exactamente a esta venta.
     */
        $responseCode = $response->getResponseCode();
        $status = $response->getStatus();

        $ordenCoincide =
            $response->getBuyOrder() === $venta->webpay_buy_order;

        $montoCoincide =
            (int) round((float) $response->getAmount())
            === (int) round((float) $venta->total);

        if (!$ordenCoincide || !$montoCoincide) {

            Log::error('Webpay: orden o monto no coinciden', [
                'venta_id' => $venta->id,
                'orden_coincide' => $ordenCoincide,
                'monto_coincide' => $montoCoincide,
            ]);

            return redirect()
                ->route('ventas.create')
                ->with(
                    'error',
                    'No fue posible validar los datos del pago. '
                        . 'Contacta a soporte con el folio '
                        . $venta->folio . '.'
                );
        }

        /*
     * 4. Pago autorizado por Transbank.
     */
        if ($status === 'AUTHORIZED' && $responseCode === 0) {

            $cardDetail = $response->getCardDetail();
            $cardNumber = null;

            if (is_array($cardDetail)) {
                $cardNumber = $cardDetail['card_number'] ?? null;
            }

            /*
         * Evitar que dos retornos simultáneos
         * confirmen nuevamente la misma venta.
         */
            $actualizada = \Illuminate\Support\Facades\DB::transaction(
                function () use ($venta, $response, $cardNumber) {

                    $registro = Venta::whereKey($venta->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($registro->estado === 'PAGADA') {
                        return false;
                    }

                    if ($registro->estado !== 'PENDIENTE_PAGO') {
                        throw new \RuntimeException(
                            'La venta no está pendiente de pago.'
                        );
                    }

                    $registro->update([
                        'estado' => 'PAGADA',

                        'webpay_authorization_code' =>
                        $response->getAuthorizationCode(),

                        'webpay_response_code' => 0,

                        'webpay_payment_type_code' =>
                        $response->getPaymentTypeCode(),

                        'webpay_card_number' => $cardNumber,

                        'pagada_at' => $registro->pagada_at ?? now(),

                        'token_ticket' => $registro->token_ticket
                            ?? (string) \Illuminate\Support\Str::uuid(),
                    ]);

                    return true;
                }
            );

            $venta->refresh();

            if (!$actualizada) {
                return redirect()->route('ventas.show', $venta);
            }

            /*
         * 5. Enviar ticket por correo.
         *    Un error de correo no revierte el pago.
         */
            try {

                Mail::to($venta->correo)
                    ->send(new TicketCompradoMail($venta));

                $venta->update([
                    'ticket_enviado_at' => now(),
                ]);

                return redirect()
                    ->route('ventas.show', $venta)
                    ->with(
                        'success',
                        'Pago confirmado correctamente. '
                            . 'Hemos enviado tu ticket al correo '
                            . $venta->correo . '.'
                    );
            } catch (\Throwable $e) {

                Log::error('Error enviando ticket', [
                    'venta_id' => $venta->id,
                    'error' => $e->getMessage(),
                ]);

                return redirect()
                    ->route('ventas.show', $venta)
                    ->with(
                        'warning',
                        'Pago confirmado, pero no fue posible '
                            . 'enviar el correo con tu ticket.'
                    );
            }
        }

        /*
     * 6. Solo marcar FALLIDA cuando Webpay
     *    informe expresamente FAILED.
     *
     * INITIALIZED u otros estados no concluyentes
     * no deben interpretarse como pagos rechazados.
     */
        if ($status === 'FAILED') {

            $venta->update([
                'estado' => 'FALLIDA',
                'webpay_response_code' => $responseCode,
            ]);

            return redirect()
                ->route('webpay.fallo', $venta)
                ->with('error', 'El pago fue rechazado.');
        }

        Log::warning('Webpay: estado no confirmado', [
            'venta_id' => $venta->id,
            'status' => $status,
            'response_code' => $responseCode,
        ]);

        return redirect()
            ->route('ventas.create')
            ->with(
                'error',
                'El pago no pudo confirmarse. '
                    . 'Antes de intentar nuevamente, consulta '
                    . 'con soporte e indica el folio '
                    . $venta->folio . '.'
            );
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
