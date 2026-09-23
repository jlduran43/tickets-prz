<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\TicketOfflineSigner;
use Endroid\QrCode\Color\Color;

class TicketController extends Controller
{
    public function descargarPdf(string $token)
    {
        $venta = Venta::where('token_ticket', $token)->firstOrFail();

        if ($venta->estado !== 'PAGADA') {
            abort(404);
        }

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

        $result = $writer->write($qrCode);

        $qrBase64 = base64_encode(
            $result->getString()
        );

        $vencimiento = $venta->pagada_at
            ? $venta->pagada_at->copy()->addMonths(3)
            : null;

        $pdf = Pdf::loadView(
            'tickets.pdf',
            compact(
                'venta',
                'qrBase64',
                'vencimiento'
            )
        );

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download(
            'ticket-' . $venta->folio . '.pdf'
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Consulta pública del ticket
    |--------------------------------------------------------------------------
    |
    | Esta URL está contenida en el QR.
    |
    | IMPORTANTE:
    | Aquí NO se modifica validada_at.
    |
    | Por lo tanto, si el cliente escanea su propio QR,
    | no pierde ni consume el ticket.
    |
    */

    public function verificar(string $token)
    {
        $venta = Venta::where('token_ticket', $token)->first();

        if (!$venta) {
            return view('tickets.invalido');
        }

        if ($venta->estado !== 'PAGADA') {
            return view('tickets.invalido', compact('venta'));
        }

        /*
    |--------------------------------------------------------------------------
    | FECHA DE VENCIMIENTO
    |--------------------------------------------------------------------------
    */

        $vencimiento = $venta->pagada_at
            ? $venta->pagada_at->copy()->addMonths(3)
            : null;


        /*
    |--------------------------------------------------------------------------
    | TICKET VENCIDO
    |--------------------------------------------------------------------------
    */

        if (
            $vencimiento &&
            now()->greaterThan($vencimiento)
        ) {
            return view(
                'tickets.vencido',
                compact('venta', 'vencimiento')
            );
        }


        /*
    |--------------------------------------------------------------------------
    | TICKET YA UTILIZADO
    |--------------------------------------------------------------------------
    */

        if ($venta->validada_at) {

            return view(
                'tickets.utilizado',
                compact('venta', 'vencimiento')
            );
        }


        /*
    |--------------------------------------------------------------------------
    | TICKET VIGENTE
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE:
    | Escanearlo públicamente NO lo marca como utilizado.
    |
    */

        return view(
            'tickets.consulta',
            compact('venta', 'vencimiento')
        );
    }
}
