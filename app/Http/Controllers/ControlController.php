<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

                if ($venta->estado !== 'PAGADA') {

                    return response()->json([
                        'ok' => false,
                        'estado' => 'INVALIDO',
                        'mensaje' => 'El ticket no se encuentra pagado.',
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

    public function sincronizarOffline(Request $request)
    {
        \Log::info('ENTRO A sincronizarOffline', [
            'data' => $request->all()
        ]);

        $request->validate([
            'token' => 'required|string',
            'venta_id' => 'required|integer',
            'folio' => 'required|string',
            'scanned_at' => 'required|date',
            'scan_uuid' => 'required|string',
            'device_id' => 'nullable|string',
        ]);


    $venta = Venta::where(
        'id',
        $request->venta_id
    )
        ->where(
            'token_ticket',
            $request->token
        )
        ->first();


    if (!$venta) {

        return response()->json([
            'ok' => false,
            'mensaje' => 'Ticket no encontrado.'
        ], 404);
    }


    /*
    |--------------------------------------------------------------------------
    | SI YA ESTÁ UTILIZADO
    |--------------------------------------------------------------------------
    */

    if ($venta->validada_at) {

        return response()->json([
            'ok' => true,
            'estado' => 'YA_SINCRONIZADO',
            'folio' => $venta->folio,
            'validada_at' => $venta->validada_at,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR FECHA REAL DEL ESCANEO OFFLINE
    |--------------------------------------------------------------------------
    */

    $venta->validada_at =
        \Carbon\Carbon::parse(
            $request->scanned_at
        );

    $venta->save();


    return response()->json([
        'ok' => true,
        'estado' => 'SINCRONIZADO',
        'folio' => $venta->folio,
        'validada_at' => $venta->validada_at,
    ]);
}
}
