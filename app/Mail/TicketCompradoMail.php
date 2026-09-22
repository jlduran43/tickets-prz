<?php

namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class TicketCompradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;
    public string $qrImage;

    public function __construct(public Venta $venta)
    {
        $this->url =
            rtrim(config('app.url'), '/')
            . route(
                'ticket.verificar',
                ['token' => $venta->token_ticket],
                false
            );

        $qrCode = new QrCode(
            data: $this->url,
            size: 300,
            margin: 10,
            foregroundColor: new Color(20, 110, 70),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $this->qrImage = $result->getString();
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