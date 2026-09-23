<?php

namespace App\Services;

use App\Models\Venta;
use RuntimeException;

class TicketOfflineSigner
{
    private string $privateKeyPath;
    private string $publicKeyPath;

    public function __construct()
    {
        $this->privateKeyPath =
            storage_path('app/private/tickets_private.pem');

        $this->publicKeyPath =
            public_path('offline/tickets_public.pem');
    }

    public function generarQrFirmado(Venta $venta): string
    {
        if (!$venta->pagada_at) {
            throw new RuntimeException(
                'La venta todavía no está pagada.'
            );
        }

        if (!$venta->token_ticket) {
            throw new RuntimeException(
                'La venta no tiene token de ticket.'
            );
        }

        $emitido = $venta->pagada_at->copy();

        $vence = $emitido
            ->copy()
            ->addMonthsNoOverflow(3);

        $payload = [
            'v' => 1,
            'id' => (int) $venta->id,
            'folio' => $venta->folio,
            'token' => $venta->token_ticket,
            'iat' => $emitido->timestamp,
            'exp' => $vence->timestamp,
        ];

        $json = json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE
        );

        $payloadBase64 = $this->base64UrlEncode($json);

        if (!file_exists($this->privateKeyPath)) {
            throw new RuntimeException(
                'No existe la clave privada.'
            );
        }

        $privateKey = openssl_pkey_get_private(
            file_get_contents($this->privateKeyPath)
        );

        if (!$privateKey) {
            throw new RuntimeException(
                'No fue posible leer la clave privada.'
            );
        }

        $ok = openssl_sign(
            $payloadBase64,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        if (!$ok) {
            throw new RuntimeException(
                'No fue posible firmar el ticket.'
            );
        }

        $signatureBase64 =
            $this->base64UrlEncode($signature);

        return sprintf(
            'PRZ1.%s.%s',
            $payloadBase64,
            $signatureBase64
        );
    }

    public function verificar(string $codigo): ?array
    {
        $partes = explode('.', trim($codigo));

        if (count($partes) !== 3) {
            return null;
        }

        [$version, $payloadBase64, $signatureBase64] = $partes;

        if ($version !== 'PRZ1') {
            return null;
        }

        if (!file_exists($this->publicKeyPath)) {
            return null;
        }

        $publicKey = openssl_pkey_get_public(
            file_get_contents($this->publicKeyPath)
        );

        if (!$publicKey) {
            return null;
        }

        $signature =
            $this->base64UrlDecode($signatureBase64);

        $resultado = openssl_verify(
            $payloadBase64,
            $signature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        if ($resultado !== 1) {
            return null;
        }

        $json =
            $this->base64UrlDecode($payloadBase64);

        $payload = json_decode(
            $json,
            true
        );

        if (!is_array($payload)) {
            return null;
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(
            strtr(
                base64_encode($data),
                '+/',
                '-_'
            ),
            '='
        );
    }

    private function base64UrlDecode(string $data): string
    {
        $padding = strlen($data) % 4;

        if ($padding) {
            $data .= str_repeat(
                '=',
                4 - $padding
            );
        }

        return base64_decode(
            strtr(
                $data,
                '-_',
                '+/'
            )
        );
    }
}