<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\TicketOfflineScan;
use App\Services\TicketOfflineSigner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfflineTicketController extends Controller
{
    public function sincronizar(
        Request $request,
        TicketOfflineSigner $signer
    ) {

        $datos =
            $request->validate([
                'scan_uuid' =>
                    'required|uuid',

                'signed_qr' =>
                    'required|string',

                'device_id' =>
                    'required|string|max:255',

                'scanned_at' =>
                    'required|date',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Evitar procesar dos veces la misma sincronización
        |--------------------------------------------------------------------------
        */

        $existente =
            TicketOfflineScan::where(
                'scan_uuid',
                $datos['scan_uuid']
            )->first();


        if ($existente) {

            return response()->json([
                'ok' => true,
                'resultado' =>
                    $existente->resultado,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validar firma
        |--------------------------------------------------------------------------
        */

        $payload =
            $signer->verificar(
                $datos['signed_qr']
            );


        if (!$payload) {

            return response()->json([
                'ok' => false,
                'resultado' =>
                    'FIRMA_INVALIDA',
            ], 422);
        }


        if (
            empty($payload['id']) ||
            empty($payload['token']) ||
            empty($payload['exp'])
        ) {

            return response()->json([
                'ok' => false,
                'resultado' =>
                    'PAYLOAD_INVALIDO',
            ], 422);
        }


        $scanDate =
            Carbon::parse(
                $datos['scanned_at']
            );


        /*
        |--------------------------------------------------------------------------
        | Comprobar que no estaba vencido cuando se escaneó
        |--------------------------------------------------------------------------
        */

        if (
            $scanDate->timestamp >
            $payload['exp']
        ) {

            return response()->json([
                'ok' => false,
                'resultado' =>
                    'VENCIDO',
            ], 422);
        }


        return DB::transaction(
            function () use (
                $payload,
                $datos,
                $scanDate
            ) {

                $venta =
                    Venta::where(
                        'id',
                        $payload['id']
                    )
                    ->where(
                        'token_ticket',
                        $payload['token']
                    )
                    ->lockForUpdate()
                    ->first();


                if (!$venta) {

                    $this->guardarScan(
                        datos:
                            $datos,

                        payload:
                            $payload,

                        venta:
                            null,

                        resultado:
                            'NO_EXISTE',

                        scanDate:
                            $scanDate
                    );


                    return response()->json([
                        'ok' => false,
                        'resultado' =>
                            'NO_EXISTE',
                    ], 404);
                }


                /*
                |--------------------------------------------------------------------------
                | Comprobar pago
                |--------------------------------------------------------------------------
                */

                if (
                    $venta->estado_pago
                    !== 'PAGADO'
                ) {

                    $this->guardarScan(
                        $datos,
                        $payload,
                        $venta,
                        'NO_PAGADO',
                        $scanDate
                    );


                    return response()->json([
                        'ok' => false,
                        'resultado' =>
                            'NO_PAGADO',
                    ], 422);
                }


                /*
                |--------------------------------------------------------------------------
                | Ya estaba utilizado en servidor
                |--------------------------------------------------------------------------
                */

                if ($venta->validada_at) {

                    $this->guardarScan(
                        $datos,
                        $payload,
                        $venta,
                        'YA_UTILIZADO',
                        $scanDate
                    );


                    return response()->json([
                        'ok' => false,
                        'resultado' =>
                            'YA_UTILIZADO',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDACIÓN CORRECTA
                |--------------------------------------------------------------------------
                */

                $venta->validada_at =
                    $scanDate;

                $venta->save();


                $this->guardarScan(
                    $datos,
                    $payload,
                    $venta,
                    'VALIDADO',
                    $scanDate
                );


                return response()->json([
                    'ok' => true,
                    'resultado' =>
                        'VALIDADO',

                    'folio' =>
                        $venta->folio,
                ]);
            }
        );
    }


    private function guardarScan(
        array $datos,
        array $payload,
        ?Venta $venta,
        string $resultado,
        Carbon $scanDate
    ): void {

        TicketOfflineScan::create([

            'scan_uuid' =>
                $datos['scan_uuid'],

            'venta_id' =>
                $venta?->id,

            'token_ticket' =>
                $payload['token']
                ?? null,

            'device_id' =>
                $datos['device_id'],

            'scanned_at' =>
                $scanDate,

            'received_at' =>
                now(),

            'resultado' =>
                $resultado,

            'payload' =>
                $payload,
        ]);
    }
}