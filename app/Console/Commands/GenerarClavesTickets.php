<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerarClavesTickets extends Command
{
    protected $signature = 'tickets:generar-claves';

    protected $description = 'Genera las claves RSA para firma offline de tickets';

    public function handle(): int
    {
        $this->info('Generando claves RSA...');

        $config = [
            'config' => 'C:/xampp/php/extras/ssl/openssl.cnf',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $privateKey = openssl_pkey_new($config);

        if ($privateKey === false) {

            $this->error('No fue posible generar la clave RSA.');

            while ($error = openssl_error_string()) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $details = openssl_pkey_get_details($privateKey);

        if ($details === false || empty($details['key'])) {
            $this->error('No fue posible obtener la clave pública.');

            while ($error = openssl_error_string()) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $publicKey = $details['key'];

        $privateKeyPem = '';

        if (!openssl_pkey_export($privateKey, $privateKeyPem)) {

            $this->error('No fue posible exportar la clave privada.');

            while ($error = openssl_error_string()) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $keysDirectory = storage_path('app/keys');

        if (!is_dir($keysDirectory)) {
            mkdir($keysDirectory, 0700, true);
        }

        file_put_contents(
            $keysDirectory . '/ticket_private.pem',
            $privateKeyPem
        );

        file_put_contents(
            $keysDirectory . '/ticket_public.pem',
            $publicKey
        );

        $this->info('Claves generadas correctamente.');
        $this->line('');
        $this->line('Privada: storage/app/keys/ticket_private.pem');
        $this->line('Pública: storage/app/keys/ticket_public.pem');

        return self::SUCCESS;
    }
}
