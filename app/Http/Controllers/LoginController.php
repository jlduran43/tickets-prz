<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Venta;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }


    public function store(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (!Auth::attempt($credenciales, $request->boolean('remember'))) {

            return back()
                ->withErrors([
                    'email' => 'Las credenciales ingresadas no son correctas.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerar sesión
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Redirección según rol
        |--------------------------------------------------------------------------
        */

        if (
            $usuario->rol === 'CLIENTE' &&
            !$usuario->hasVerifiedEmail()
        ) {
            return redirect()
                ->route('verification.notice');
        }


        if ($usuario->rol === 'ADMIN') {

            return redirect()
                ->route('admin.usuarios.index');
        }


        if ($usuario->rol === 'CONTROL') {

            return redirect()
                ->route('control.index');
        }


        /*
        |--------------------------------------------------------------------------
        | Cliente
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('ventas.create');
    }


    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login');
    }

    public function sincronizarOffline(Request $request)
    {
        \Log::info('ENTRO A sincronizarOffline', [
            'data' => $request->all()
        ]);

        $request->validate([
            'token' => 'required|string',
            'venta_id' => 'required|integer',
            'folio' => 'required|string',
            'scanned_at' => 'required|date',
            'scan_uuid' => 'required|string',
            'device_id' => 'nullable|string',
        ]);


    $venta = Venta::where(
        'id',
        $request->venta_id
    )
        ->where(
            'token_ticket',
            $request->token
        )
        ->first();


    if (!$venta) {

        return response()->json([
            'ok' => false,
            'mensaje' => 'Ticket no encontrado.'
        ], 404);
    }


    /*
    |--------------------------------------------------------------------------
    | SI YA ESTÁ UTILIZADO
    |--------------------------------------------------------------------------
    */

    if ($venta->validada_at) {

        return response()->json([
            'ok' => true,
            'estado' => 'YA_SINCRONIZADO',
            'folio' => $venta->folio,
            'validada_at' => $venta->validada_at,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR FECHA REAL DEL ESCANEO OFFLINE
    |--------------------------------------------------------------------------
    */

    $venta->validada_at =
        \Carbon\Carbon::parse(
            $request->scanned_at
        );

    $venta->save();


    return response()->json([
        'ok' => true,
        'estado' => 'SINCRONIZADO',
        'folio' => $venta->folio,
        'validada_at' => $venta->validada_at,
    ]);
}
}
