<?php

namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use App\Services\TicketOfflineSigner;

class TicketCompradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $codigoQr;
    public string $qrBase64;

    public function __construct(public Venta $venta)
    {
        $signer = app(TicketOfflineSigner::class);

        $this->codigoQr =
            $signer->generarQrFirmado($venta);

        $qrCode = new QrCode(
            data: $this->codigoQr,
            size: 300,
            margin: 10,
            foregroundColor: new Color(20, 110, 70),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();

        $result = $writer->write($qrCode);

        $this->qrBase64 =
            base64_encode(
                $result->getString()
            );
    }

    public function build()
    {
        return $this
            ->subject(
                'Tu ticket de acceso PRZ - ' . $this->venta->folio
            )
            ->view('emails.ticket-comprado');
    }
}
