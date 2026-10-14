<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\TicketOfflineSigner;

class ControlController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Inicio control
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('control.index');
    }


    /*
    |--------------------------------------------------------------------------
    | Escáner
    |--------------------------------------------------------------------------
    */

    public function scanner()
    {
        return view('control.scanner');
    }


    /*
    |--------------------------------------------------------------------------
    | Historial
    |--------------------------------------------------------------------------
    */

    public function historial()
    {
        $ventas = Venta::with('usuarioValidador')
            ->whereNotNull('validada_at')
            ->orderByDesc('validada_at')
            ->limit(100)
            ->get();

        return view(
            'control.historial',
            compact('ventas')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR TICKET
    |--------------------------------------------------------------------------
    |
    | Esta es la ÚNICA operación que marca el ticket
    | como utilizado.
    |
    | Solamente puede acceder un usuario CONTROL
    | mediante middleware.
    |
    */

    public function validar(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validación entrada
        |--------------------------------------------------------------------------
        */

        $datos = $request->validate([

            'token' => [
                'required',
                'string',
                'max:255',
            ],

        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | Transacción
            |--------------------------------------------------------------------------
            |
            | lockForUpdate evita que dos teléfonos de control
            | puedan validar exactamente el mismo ticket al mismo tiempo.
            |
            */

            return DB::transaction(function () use ($datos) {

                $venta = Venta::where(
                    'token_ticket',
                    $datos['token']
                )
                    ->lockForUpdate()
                    ->first();


                /*
                |--------------------------------------------------------------------------
                | No existe
                |--------------------------------------------------------------------------
                */

                if (!$venta) {

                    return response()->json([
                        'ok' => false,
                        'estado' => 'INVALIDO',
                        'mensaje' => 'El ticket no existe.',
                    ], 404);
                }


                /*
                |--------------------------------------------------------------------------
                | No pagado
                |--------------------------------------------------------------------------
                */

                if (
                    $venta->estado !== 'PAGADA' ||
                    blank($venta->token_ticket) ||
                    !$venta->pagada_at
                ) {
                    return response()->json([
                        'ok' => false,
                        'estado' => 'INVALIDO',
                        'mensaje' => 'El ticket no se encuentra pagado o está incompleto.',
                        'folio' => $venta->folio,
                    ], 422);
                }


                /*
                |--------------------------------------------------------------------------
                | Vencido
                |--------------------------------------------------------------------------
                */

                $fechaVencimiento =
                    $venta->pagada_at
                    ? $venta->pagada_at
                    ->copy()
                    ->addMonths(3)
                    : null;


                if (
                    $fechaVencimiento &&
                    now()->greaterThan($fechaVencimiento)
                ) {

                    return response()->json([
                        'ok' => false,
                        'estado' => 'VENCIDO',
                        'mensaje' => 'El ticket ha superado su período de vigencia.',
                        'folio' => $venta->folio,
                        'visitante' => $venta->nombre_cliente,
                        'vencimiento' => $fechaVencimiento
                            ->format('d/m/Y H:i'),
                    ], 422);
                }


                /*
                |--------------------------------------------------------------------------
                | Ya utilizado
                |--------------------------------------------------------------------------
                */

                if ($venta->validada_at) {

                    return response()->json([
                        'ok' => false,
                        'estado' => 'UTILIZADO',
                        'mensaje' => 'Este ticket ya fue utilizado.',
                        'folio' => $venta->folio,
                        'visitante' => $venta->nombre_cliente,
                        'validada_at' => $venta->validada_at
                            ->format('d/m/Y H:i'),
                    ], 409);
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDACIÓN CORRECTA
                |--------------------------------------------------------------------------
                */

                $venta->update([

                    'validada_at' => now(),

                    'validada_por' => auth()->id(),

                ]);


                Log::info(
                    'Ticket validado',
                    [
                        'venta_id' => $venta->id,
                        'folio' => $venta->folio,
                        'usuario_control_id' => auth()->id(),
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Respuesta
                |--------------------------------------------------------------------------
                */

                return response()->json([

                    'ok' => true,

                    'estado' => 'VALIDO',

                    'mensaje' =>
                    'Ticket validado correctamente.',

                    'folio' =>
                    $venta->folio,

                    'visitante' =>
                    $venta->nombre_cliente,

                    'rut' =>
                    $venta->rut_cliente,

                    'personas' =>
                    $venta->cantidad_personas,

                    'validada_at' =>
                    $venta->validada_at
                        ->format('d/m/Y H:i'),

                ]);
            });
        } catch (\Throwable $e) {

            Log::error(
                'Error validando ticket',
                [
                    'error' => $e->getMessage(),
                    'usuario' => auth()->id(),
                ]
            );


            return response()->json([
                'ok' => false,
                'estado' => 'ERROR',
                'mensaje' =>
                'No fue posible validar el ticket.',
            ], 500);
        }
    }

    public function sincronizarOffline(
        Request $request,
        TicketOfflineSigner $signer
    ) {
        $datos = $request->validate([
            'signed_qr' => 'required|string|max:8192',
            'token' => 'required|string',
            'venta_id' => 'required|integer',
            'folio' => 'required|string',
            'scanned_at' => 'required|date',
            'scan_uuid' => 'required|uuid',
            'device_id' => 'nullable|string|max:255',
        ]);

        /*
    |--------------------------------------------------------------------------
    | VERIFICAR FIRMA DIGITAL RSA
    |--------------------------------------------------------------------------
    */

        $payload = $signer->verificar($datos['signed_qr']);

        if (
            !$payload ||
            ($payload['v'] ?? null) !== 1 ||
            !isset(
                $payload['id'],
                $payload['folio'],
                $payload['token'],
                $payload['iat'],
                $payload['exp']
            )
        ) {
            return response()->json([
                'ok' => false,
                'estado' => 'FIRMA_INVALIDA',
                'mensaje' => 'La firma digital del ticket no es válida.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | COMPROBAR DATOS DEL QR
    |--------------------------------------------------------------------------
    */

        if (
            !is_int($payload['id']) ||
            !is_string($payload['folio']) ||
            !is_string($payload['token']) ||
            !is_int($payload['iat']) ||
            !is_int($payload['exp']) ||
            $payload['id'] !== (int) $datos['venta_id'] ||
            $payload['folio'] !== $datos['folio'] ||
            $payload['token'] !== $datos['token']
        ) {
            return response()->json([
                'ok' => false,
                'estado' => 'DATOS_INVALIDOS',
                'mensaje' => 'Los datos del QR no coinciden.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | VALIDAR FECHA DE ESCANEO
    |--------------------------------------------------------------------------
    */

        $fechaEscaneo = \Carbon\Carbon::parse(
            $datos['scanned_at']
        );

        $ahora = now();

        if ($fechaEscaneo->greaterThan($ahora->copy()->addMinutes(5))) {
            return response()->json([
                'ok' => false,
                'estado' => 'FECHA_INVALIDA',
                'mensaje' => 'La fecha de escaneo es posterior a la fecha actual.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | TRANSACCIÓN PROTEGIDA
    |--------------------------------------------------------------------------
    */

        try {
            return DB::transaction(function () use (
                $datos,
                $payload,
                $fechaEscaneo
            ) {
                $venta = Venta::whereKey($datos['venta_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$venta) {
                    return response()->json([
                        'ok' => false,
                        'estado' => 'INVALIDO',
                        'mensaje' => 'Ticket no encontrado.',
                    ], 404);
                }

                /*
            | Comprobar identidad y estado.
            */

                if (
                    $venta->folio !== $payload['folio'] ||
                    $venta->token_ticket !== $payload['token'] ||
                    $venta->estado !== 'PAGADA' ||
                    !$venta->pagada_at
                ) {
                    return response()->json([
                        'ok' => false,
                        'estado' => 'INVALIDO',
                        'mensaje' => 'El ticket no tiene un pago válido.',
                    ], 422);
                }

                /*
            | Comprobar fechas firmadas contra la venta.
            */

                $vencimiento = $venta->pagada_at
                    ->copy()
                    ->addMonthsNoOverflow(3);

                if (
                    $payload['iat'] !== $venta->pagada_at->timestamp ||
                    $payload['exp'] !== $vencimiento->timestamp
                ) {
                    return response()->json([
                        'ok' => false,
                        'estado' => 'DATOS_INVALIDOS',
                        'mensaje' => 'Las fechas del QR no coinciden.',
                    ], 422);
                }

                /*
            | Validar que el escaneo se realizó
            | durante la vigencia del ticket.
            */

                if (
                    $fechaEscaneo->lt($venta->pagada_at) ||
                    $fechaEscaneo->gt($vencimiento)
                ) {
                    return response()->json([
                        'ok' => false,
                        'estado' => 'VENCIDO',
                        'mensaje' => 'El escaneo está fuera de vigencia.',
                    ], 422);
                }

                /*
            | Ticket utilizado previamente.
            */

                if ($venta->validada_at) {
                    return response()->json([
                        'ok' => true,
                        'estado' => 'YA_UTILIZADO',
                        'mensaje' => 'El ticket ya estaba utilizado.',
                        'folio' => $venta->folio,
                        'validada_at' => $venta->validada_at,
                    ]);
                }

                /*
            | Registrar utilización.
            */

                $venta->validada_at = $fechaEscaneo;
                $venta->validada_por = auth()->id();
                $venta->save();

                Log::info('Ticket offline sincronizado', [
                    'venta_id' => $venta->id,
                    'folio' => $venta->folio,
                    'scan_uuid' => $datos['scan_uuid'],
                    'device_id' => $datos['device_id'] ?? null,
                ]);

                return response()->json([
                    'ok' => true,
                    'estado' => 'SINCRONIZADO',
                    'folio' => $venta->folio,
                    'validada_at' => $venta->validada_at,
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Error sincronizando ticket offline', [
                'error' => $e->getMessage(),
                'scan_uuid' => $datos['scan_uuid'],
            ]);

            return response()->json([
                'ok' => false,
                'estado' => 'ERROR',
                'mensaje' => 'No fue posible sincronizar el ticket.',
            ], 500);
        }
    }
}
