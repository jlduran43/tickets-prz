<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegistroClienteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\WebpayController;
use App\Http\Controllers\ControlController;

use App\Models\Comuna;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


/*
|--------------------------------------------------------------------------
| Página inicial
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $usuario = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Cliente sin verificar
    |--------------------------------------------------------------------------
    */

    if (
        $usuario->rol === 'CLIENTE' &&
        !$usuario->hasVerifiedEmail()
    ) {
        return redirect()
            ->route('verification.notice');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    if ($usuario->rol === 'ADMIN') {
        return redirect()
            ->route('admin.usuarios.index');
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROL
    |--------------------------------------------------------------------------
    */

    if ($usuario->rol === 'CONTROL') {
        return redirect()
            ->route('control.index');
    }

    /*
    |--------------------------------------------------------------------------
    | CLIENTE
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('ventas.create');
});


/*
|--------------------------------------------------------------------------
| Rutas para invitados
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/login',
        [LoginController::class, 'create']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'store']
    )->name('login.store');


    /*
    |--------------------------------------------------------------------------
    | Registro
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/registro',
        [RegistroClienteController::class, 'create']
    )->name('registro');

    Route::post(
        '/registro',
        [RegistroClienteController::class, 'store']
    )->name('registro.store');


    /*
    |--------------------------------------------------------------------------
    | Validación de correo disponible
    |--------------------------------------------------------------------------
    */

    Route::get('/validar-email', function (Request $request) {

        $email = strtolower(
            trim(
                $request->query('email', '')
            )
        );

        if ($email === '') {

            return response()->json([
                'existe' => false,
            ]);
        }

        return response()->json([
            'existe' => User::where(
                'email',
                $email
            )->exists(),
        ]);

    })->name('validar.email');


    /*
    |--------------------------------------------------------------------------
    | Recuperación de contraseña
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/olvide-mi-password',
        [PasswordResetController::class, 'request']
    )->name('password.request');

    Route::post(
        '/olvide-mi-password',
        [PasswordResetController::class, 'email']
    )->name('password.email');

    Route::get(
        '/restablecer-password/{token}',
        [PasswordResetController::class, 'reset']
    )->name('password.reset');

    Route::post(
        '/restablecer-password',
        [PasswordResetController::class, 'update']
    )->name('password.update');

});


/*
|--------------------------------------------------------------------------
| Verificación de correo
|--------------------------------------------------------------------------
|
| Estas rutas deben estar FUERA del middleware guest,
| porque requieren que el usuario esté autenticado.
|
*/

Route::get(
    '/email/verificar',
    function () {

        return view('auth.verify-email');

    }
)
    ->middleware('auth')
    ->name('verification.notice');


Route::get(
    '/email/verificar/{id}/{hash}',
    function (EmailVerificationRequest $request) {

        /*
        |--------------------------------------------------------------------------
        | Marcar correo como verificado
        |--------------------------------------------------------------------------
        */

        $request->fulfill();

        /*
        |--------------------------------------------------------------------------
        | Después de verificar
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('ventas.create')
            ->with(
                'success',
                'Tu correo electrónico fue verificado correctamente.'
            );

    }
)
    ->middleware([
        'auth',
        'signed',
    ])
    ->name('verification.verify');


Route::post(
    '/email/reenviar-verificacion',
    function (Request $request) {

        /*
        |--------------------------------------------------------------------------
        | Si ya está verificado
        |--------------------------------------------------------------------------
        */

        if ($request->user()->hasVerifiedEmail()) {

            return redirect()
                ->route('ventas.create');
        }

        /*
        |--------------------------------------------------------------------------
        | Reenviar correo
        |--------------------------------------------------------------------------
        */

        $request
            ->user()
            ->sendEmailVerificationNotification();

        return back()->with(
            'status',
            'verification-link-sent'
        );

    }
)
    ->middleware([
        'auth',
        'throttle:6,1',
    ])
    ->name('verification.send');


/*
|--------------------------------------------------------------------------
| Cerrar sesión
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [LoginController::class, 'destroy']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Comunas por región
|--------------------------------------------------------------------------
*/

Route::get(
    '/comunas/{region}',
    function ($region) {

        return Comuna::where(
            'region_id',
            $region
        )
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
            ]);
    }
)->name('comunas.region');


/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Retorno Webpay
|--------------------------------------------------------------------------
|
| Transbank necesita acceder a esta URL sin login.
|
*/

Route::any(
    '/webpay/retorno',
    [WebpayController::class, 'retorno']
)->name('webpay.retorno');


/*
|--------------------------------------------------------------------------
| Verificación pública del ticket QR
|--------------------------------------------------------------------------
|
| El teléfono que escanea el QR no necesita iniciar sesión.
|
*/

Route::get(
    '/ticket/verificar/{token}',
    [TicketController::class, 'verificar']
)->name('ticket.verificar');


Route::get(
    '/ticket/{token}/pdf',
    [TicketController::class, 'descargarPdf']
)->name('ticket.pdf.descargar');


/*
|--------------------------------------------------------------------------
| Rutas protegidas para clientes verificados
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Ventas
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/ventas/crear',
        [VentaController::class, 'create']
    )->name('ventas.create');

    Route::post(
        '/ventas',
        [VentaController::class, 'store']
    )->name('ventas.store');

    Route::get(
        '/ventas/{venta}',
        [VentaController::class, 'show']
    )->name('ventas.show');


    /*
    |--------------------------------------------------------------------------
    | Webpay
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/webpay/iniciar/{venta}',
        [WebpayController::class, 'iniciar']
    )->name('webpay.iniciar');

    Route::get(
        '/webpay/exito/{venta}',
        [WebpayController::class, 'exito']
    )->name('webpay.exito');

    Route::get(
        '/webpay/fallo/{venta?}',
        [WebpayController::class, 'fallo']
    )->name('webpay.fallo');


    /*
    |--------------------------------------------------------------------------
    | Mis tickets
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/mis-tickets',
        [VentaController::class, 'misTickets']
    )->name('tickets.index');

    Route::get(
        '/mis-tickets/{venta}',
        [VentaController::class, 'verTicket']
    )->name('tickets.show');

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:ADMIN',
])->group(function () {

    Route::get(
        '/admin/usuarios',
        [UsuarioController::class, 'index']
    )->name('admin.usuarios.index');

    Route::get(
        '/admin/usuarios/crear',
        [UsuarioController::class, 'create']
    )->name('admin.usuarios.create');

    Route::post(
        '/admin/usuarios',
        [UsuarioController::class, 'store']
    )->name('admin.usuarios.store');

});


/*
|--------------------------------------------------------------------------
| CONTROL
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:CONTROL',
])->group(function () {

    Route::get(
        '/control',
        [ControlController::class, 'index']
    )->name('control.index');

    Route::get(
        '/control/escaner',
        [ControlController::class, 'scanner']
    )->name('control.scanner');

    Route::post(
        '/control/validar',
        [ControlController::class, 'validar']
    )->name('control.validar');

    Route::get(
        '/control/historial',
        [ControlController::class, 'historial']
    )->name('control.historial');

});