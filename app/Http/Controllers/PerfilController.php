<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $cliente = $user->cliente;

        $regiones = Region::orderBy('nombre')->get();

        $comunas = Comuna::where(
            'region_id',
            $cliente->region_id
        )
            ->orderBy('nombre')
            ->get();

        return view(
            'perfil.edit',
            compact(
                'user',
                'cliente',
                'regiones',
                'comunas'
            )
        );
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        $cliente = $user->cliente;

        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'telefono' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'region_id' => [
                    'required',
                    'exists:regiones,id',
                ],

                'comuna_id' => [
                    'required',
                    'exists:comunas,id',
                ],

                'direccion' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'patente' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'recibir_noticias' => [
                    'required',
                    'boolean',
                ],
            ],
            [
                'name.required' =>
                    'El nombre es obligatorio.',

                'telefono.required' =>
                    'El teléfono es obligatorio.',

                'region_id.required' =>
                    'Debes seleccionar una región.',

                'comuna_id.required' =>
                    'Debes seleccionar una comuna.',
            ]
        );


        $user->update([
            'name' => $request->name,
        ]);


        $cliente->update([
            'telefono' => $request->telefono,

            'region_id' => $request->region_id,

            'comuna_id' => $request->comuna_id,

            'direccion' =>
                $request->filled('direccion')
                    ? trim($request->direccion)
                    : null,

            'patente' =>
                $request->filled('patente')
                    ? strtoupper(
                        trim($request->patente)
                    )
                    : null,

            'recibir_noticias' =>
                $request->boolean(
                    'recibir_noticias'
                ),
        ]);


        return back()->with(
            'success',
            'Tus datos fueron actualizados correctamente.'
        );
    }
}