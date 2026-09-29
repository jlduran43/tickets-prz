<?php

namespace App\Services;

use App\Models\User;
use RuntimeException;

class OfflineAuthSigner
{
    public function generarPermiso(
        User $user,
        string $deviceId
    ): array {
        if ($user->rol !== 'CONTROL') {
            throw new RuntimeException(
                'Solo los usuarios CONTROL pueden utilizar acceso offline.'
            );
        }

        $privateKeyPath = config(
            'offline.private_key'
        );

        if (!file_exists($privateKeyPath)) {
            throw new RuntimeException(
                'No existe la clave privada del login offline.'
            );
        }

        $privateKeyContent = file_get_contents(
            $privateKeyPath
        );

        $privateKey = openssl_pkey_get_private(
            $privateKeyContent
        );

        if (!$privateKey) {
            throw new RuntimeException(
                'No fue posible cargar la clave privada.'
            );
        }

        $ahora = now();

        $vence = now()->addDays(
            (int) config('offline.login_days', 7)
        );

        $payload = [
            'v' => 1,

            'user_id' => $user->id,

            'name' => $user->name,

            'email' => $user->email,

            'role' => 'CONTROL',

            'device_id' => $deviceId,

            'iat' => $ahora->timestamp,

            'exp' => $vence->timestamp,
        ];

        $json = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
        );

        $payloadEncoded =
            $this->base64UrlEncode($json);

        $signature = '';

        $resultado = openssl_sign(
            $payloadEncoded,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        if (!$resultado) {
            throw new RuntimeException(
                'No fue posible firmar el permiso offline.'
            );
        }

        $signatureEncoded =
            $this->base64UrlEncode(
                $signature
            );

        $permit = sprintf(
            'PRZAUTH1.%s.%s',
            $payloadEncoded,
            $signatureEncoded
        );

        return [
            'permit' => $permit,
            'payload' => $payload,
            'expires_at' => $vence->toIso8601String(),
        ];
    }

    private function base64UrlEncode(
        string $data
    ): string {
        return rtrim(
            strtr(
                base64_encode($data),
                '+/',
                '-_'
            ),
            '='
        );
    }
}