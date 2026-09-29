<?php

namespace App\Http\Controllers;

use App\Models\TiposEntrada;
use Illuminate\Http\Request;

class TiposEntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposEntradas = TiposEntrada::orderBy('nombre')->get();

        return view(
            'admin.tipos-entradas.index',
            compact('tiposEntradas')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tipos-entradas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],

            'activo' => [
                'nullable',
                'boolean',
            ],
        ]);

        TiposEntrada::create([
            'nombre' => $datos['nombre'],
            'precio' => $datos['precio'],
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('admin.tipos-entradas.index')
            ->with(
                'success',
                'Tipo de entrada creado correctamente.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposEntrada $tiposEntrada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiposEntrada $tipoEntrada)
    {
        return view(
            'admin.tipos-entradas.edit',
            compact('tipoEntrada')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TiposEntrada $tipoEntrada)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],

            'activo' => [
                'nullable',
                'boolean',
            ],
        ]);

        $tipoEntrada->update([
            'nombre' => $datos['nombre'],
            'precio' => $datos['precio'],
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('admin.tipos-entradas.index')
            ->with(
                'success',
                'Tipo de entrada actualizado correctamente.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiposEntrada $tipoEntrada)
    {
        //
    }

    public function cambiarEstado(TiposEntrada $tipoEntrada)
    {
        $tipoEntrada->update([
            'activo' => ! $tipoEntrada->activo,
        ]);

        $mensaje = $tipoEntrada->activo
            ? 'Tipo de entrada activado correctamente.'
            : 'Tipo de entrada desactivado correctamente.';

        return redirect()
            ->route('admin.tipos-entradas.index')
            ->with('success', $mensaje);
    }
}
