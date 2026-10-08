<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Rules\RutChileno;
use Illuminate\Auth\Events\Registered;

class RegistroClienteController extends Controller
{
    public function create()
    {
        $regiones = Region::orderBy('nombre')->get();

        return view('auth.registro-cliente', compact('regiones'));
    }

    public function store(Request $request)
    {
        // LIMPIAR TELÉFONO CHILENO

        $telefono = preg_replace(
            '/[\s-]+/',
            '',
            (string) $request->input('telefono', '')
        );

        $request->merge([
            'telefono' => $telefono,
        ]);

        $request->validate(
            [
                'name' => 'required|string|max:255',

                'rut' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:clientes,rut',
                    new RutChileno(),
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],

                'telefono' => [
                    'required',
                    'string',
                    'regex:/^(?:\+?56)?9[0-9]{8}$/',
                ],

                'region_id' => 'required|exists:regiones,id',
                'comuna_id' => 'required|exists:comunas,id',
                'password' => 'required|string|min:8|confirmed',
            ],
            [
                'rut.unique' => 'Este RUT ya se encuentra registrado.',
                'rut.required' => 'El RUT es obligatorio.',

                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Ingresa un correo electrónico válido.',
                'email.unique' => 'Este correo electrónico ya se encuentra registrado.',

                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',

                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.regex' => 'Ingresa un celular chileno válido de 9 dígitos, comenzando con 9.',
            ]
        );

        // NORMALIZAR TELÉFONO AL FORMATO +569XXXXXXXX

        $telefono = $request->telefono;

        if (str_starts_with($telefono, '+56')) {
            $telefonoNormalizado = $telefono;
        } elseif (str_starts_with($telefono, '56')) {
            $telefonoNormalizado = '+' . $telefono;
        } else {
            $telefonoNormalizado = '+56' . $telefono;
        }

        $user = DB::transaction(function () use ($request, $telefonoNormalizado) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'CLIENTE',
            ]);

            Cliente::create([
                'user_id' => $user->id,
                'rut' => $request->rut,
                'telefono' => $telefonoNormalizado,
                'region_id' => $request->region_id,
                'comuna_id' => $request->comuna_id,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
