<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Region;
use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use App\Services\TicketOfflineSigner;
use App\Models\TiposEntrada;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regiones = Region::orderBy('nombre')->get();

        $cliente = null;


        $tipoEntrada = TiposEntrada::where('activo', true)
            ->orderBy('id')
            ->first();

        if (
            auth()->check() &&
            auth()->user()->rol === 'CLIENTE'
        ) {
            $cliente = auth()->user()->cliente;
        }

        return view('ventas.create', compact(
            'regiones',
            'cliente',
            'tipoEntrada'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== ENTRO A STORE DE VENTA ===', [
            'fecha' => now()->toDateTimeString(),
            'checkout_token' => $request->checkout_token,
        ]);

        $request->validate([
            'telefono' => [
                'required',
                'string',
                'max:20',
            ],

            'region_id' => [
                'required',
                'exists:regiones,id',
            ],

            'comuna_id' => [
                'required',
                'exists:comunas,id',
            ],

            'tipo_entrada_id' => [
                'required',
                'exists:tipos_entradas,id',
            ],

            'cantidad_personas' => [
                'required',
                'integer',
                'min:0',
            ],

            'medio_pago' => [
                'required',
                'in:WEBPAY',
            ],

            // NUEVO
            'checkout_token' => [
                'required',
                'uuid',
            ],
        ]);

        $rut = Rut::parse($request->rut_cliente);

        if (! $rut->validate()) {
            return back()
                ->withErrors([
                    'rut_cliente' => 'El RUT ingresado no es válido.',
                ])
                ->withInput();
        }

        $tipoEntrada = TiposEntrada::where(
            'id',
            $request->tipo_entrada_id
        )
            ->where('activo', true)
            ->firstOrFail();

        $precioTicket = $tipoEntrada->precio;

        /*
    |--------------------------------------------------------------------------
    | EVITAR VENTAS DUPLICADAS
    |--------------------------------------------------------------------------
    |
    | Si el mismo formulario llega varias veces,
    | checkout_token será exactamente el mismo.
    |
    */

        $venta = Venta::firstOrCreate(

            [
                'checkout_token' => $request->checkout_token,
            ],

            [
                /*
             * Folio temporal.
             *
             * Después de insertar obtenemos el ID real
             * y generamos TCK-000XXX.
             */
                'folio' => 'TMP-' . strtoupper(
                    \Illuminate\Support\Str::random(12)
                ),

                'cliente_id' =>
                auth()->user()->cliente?->id,

                'nombre_cliente' =>
                $request->nombre_cliente,

                'rut_cliente' =>
                $request->rut_cliente,

                'correo' =>
                $request->correo,

                'telefono' =>
                $request->telefono,

                'region_id' =>
                $request->region_id,

                'comuna_id' =>
                $request->comuna_id,

                'cantidad_personas' =>
                $request->cantidad_personas,

                'fecha' =>
                now()->toDateString(),

                'subtotal' =>
                $precioTicket,

                'descuento' =>
                0,

                'total' =>
                $precioTicket,

                'medio_pago' =>
                'WEBPAY',

                'estado' =>
                'PENDIENTE_PAGO',
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | GENERAR FOLIO USANDO EL ID REAL
    |--------------------------------------------------------------------------
    |
    | Solo ocurre cuando esta petición creó realmente la venta.
    |
    */

        if ($venta->wasRecentlyCreated) {

            $venta->folio =
                'TCK-' .
                str_pad(
                    $venta->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );

            $venta->save();

            Log::info('Venta creada', [
                'venta_id' => $venta->id,
                'folio' => $venta->folio,
                'checkout_token' => $venta->checkout_token,
            ]);
        } else {

            Log::warning('Intento duplicado detectado', [
                'venta_id' => $venta->id,
                'folio' => $venta->folio,
                'checkout_token' => $venta->checkout_token,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | INICIAR WEBPAY
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('webpay.iniciar', $venta);
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        abort_unless(
            auth()->check()
                &&
                $venta->cliente_id
                === auth()->user()->cliente?->id,
            403
        );

        return view(
            'ventas.show',
            compact('venta')
        );
    }

    public function exito(Venta $venta)
    {
        if ($venta->estado !== 'PAGADA') {

            return redirect()
                ->route('ventas.create');
        }

        return view(
            'webpay.exito',
            compact('venta')
        );
    }

    public function misTickets()
    {
        $usuario = auth()->user();

        $ventas = Venta::where('correo', $usuario->email)
            ->where('estado', 'PAGADA')
            ->orderByDesc('id')
            ->get();

        return view(
            'tickets.index',
            compact('ventas')
        );
    }

    public function verTicket(Venta $venta)
    {
        $usuario = auth()->user();

        /*
     * Seguridad:
     * solamente el dueño puede visualizar el ticket.
     */
        if (
            strtolower($venta->correo)
            !== strtolower($usuario->email)
        ) {
            abort(403);
        }

        if ($venta->estado !== 'PAGADA') {
            abort(404);
        }


        /*
     * Determinamos estado real del ticket.
     */
        $vencimiento = $venta->pagada_at
            ? $venta->pagada_at->copy()->addMonths(3)
            : null;


        if ($venta->validada_at) {

            $estadoTicket = 'UTILIZADO';
        } elseif (
            $vencimiento &&
            now()->greaterThan($vencimiento)
        ) {

            $estadoTicket = 'VENCIDO';
        } else {

            $estadoTicket = 'VIGENTE';
        }


        /*
     * Generamos QR solamente si está vigente.
     */
        $qrBase64 = null;

        if (
            $estadoTicket === 'VIGENTE' &&
            $venta->token_ticket
        ) {

            $signer = app(TicketOfflineSigner::class);

            $codigoQr =
                $signer->generarQrFirmado($venta);

            $qrCode = new QrCode(
                data: $codigoQr,
                size: 300,
                margin: 10,
                foregroundColor: new Color(20, 110, 70),
                backgroundColor: new Color(255, 255, 255),
            );

            $writer = new PngWriter();

            $resultado =
                $writer->write($qrCode);


            $qrBase64 = base64_encode(
                $resultado->getString()
            );
        }


        return view(
            'tickets.detalle',
            compact(
                'venta',
                'estadoTicket',
                'vencimiento',
                'qrBase64'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        //
    }
}
