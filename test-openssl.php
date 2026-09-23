<?php

$config = [
    'config' => 'C:/xampp/php/extras/ssl/openssl.cnf',
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

echo "Archivo config existe: ";
var_dump(file_exists($config['config']));

echo PHP_EOL;
echo "Intentando generar RSA..." . PHP_EOL;

$key = openssl_pkey_new($config);

if ($key === false) {
    echo "ERROR GENERANDO RSA:" . PHP_EOL;

    while ($error = openssl_error_string()) {
        echo $error . PHP_EOL;
    }

    exit(1);
}

echo "RSA GENERADA CORRECTAMENTE" . PHP_EOL;

$details = openssl_pkey_get_details($key);

echo PHP_EOL;
echo "Bits: " . ($details['bits'] ?? 'desconocido') . PHP_EOL;
echo "Tipo: " . ($details['type'] ?? 'desconocido') . PHP_EOL;