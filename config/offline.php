<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Duración autorización offline
    |--------------------------------------------------------------------------
    |
    | Cantidad de días durante los cuales un dispositivo CONTROL puede
    | autenticarse sin Internet después de haber sido preparado online.
    |
    */

    'login_days' => env('OFFLINE_LOGIN_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Claves RSA
    |--------------------------------------------------------------------------
    */

    'private_key' => storage_path(
        'app/keys/offline_auth_private.pem'
    ),

    'public_key' => public_path(
        'offline/offline_auth_public.pem'
    ),

];