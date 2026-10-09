<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

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
            $urlIntended = $request->session()->get('url.intended');

            if (
                is_string($urlIntended) &&
                URL::isValidUrl($urlIntended) &&
                str_starts_with(
                    $urlIntended,
                    url('/email/verificar/')
                )
            ) {
                return redirect()->intended(
                    route('verification.notice')
                );
            }

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
}
