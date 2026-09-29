<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')
            ->get();

        return view(
            'admin.usuarios.index',
            compact('usuarios')
        );
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'rol' => 'required|in:CONTROL,ADMIN',
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'Usuario creado correctamente.'
            );
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],

            'rol' => [
                'required',
                Rule::in([
                    'ADMIN',
                    'CONTROL',
                    'CLIENTE',
                ]),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;

        /*
        |--------------------------------------------------------------------------
        | Solo cambiar contraseña si el administrador escribió una nueva
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {
            $usuario->password = Hash::make(
                $request->password
            );
        }

        $usuario->save();

        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }

    public function destroy(User $usuario)
    {
        /*
        |--------------------------------------------------------------------------
        | Evitar que el administrador se elimine a sí mismo
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $usuario->id) {

            return redirect()
                ->route('admin.usuarios.index')
                ->with(
                    'error',
                    'No puedes eliminar tu propio usuario.'
                );
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with(
                'success',
                'Usuario eliminado correctamente.'
            );
    }

    public function cambiarEstado(User $usuario)
    {
        /*
    |--------------------------------------------------------------------------
    | No permitir que el administrador se desactive a sí mismo
    |--------------------------------------------------------------------------
    */

        if (auth()->id() === $usuario->id) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with(
                    'error',
                    'No puedes desactivar tu propio usuario.'
                );
        }

        $usuario->activo = !$usuario->activo;

        $usuario->save();

        $mensaje = $usuario->activo
            ? 'Usuario activado correctamente.'
            : 'Usuario desactivado correctamente.';

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', $mensaje);
    }
}
