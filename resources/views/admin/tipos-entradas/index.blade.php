@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between
               align-items-center mb-4">

            <div>
                <h2 class="mb-1">
                    Tipos de entradas
                </h2>

                <p class="text-muted mb-0">
                    Administración de tipos de entradas y precios.
                </p>
            </div>

            <a href="{{ route('admin.tipos-entradas.create') }}" class="btn btn-success">
                + Nuevo tipo de entrada
            </a>

        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif


        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>
                                    ID
                                </th>

                                <th>
                                    Nombre
                                </th>

                                <th>
                                    Precio
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th class="text-end">
                                    Acciones
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($tiposEntradas as $tipo)
                                <tr>

                                    <td>
                                        {{ $tipo->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $tipo->nombre }}
                                        </strong>
                                    </td>

                                    <td>
                                        <strong>
                                            ${{ number_format($tipo->precio, 0, ',', '.') }}
                                        </strong>
                                    </td>

                                    <td>

                                        @if ($tipo->activo)
                                            <span class="badge bg-success">
                                                Activo
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactivo
                                            </span>
                                        @endif

                                    </td>

                                    <td class="text-end">

                                        <a href="{{ route('admin.tipos-entradas.edit', $tipo) }}"
                                            class="btn btn-sm btn-primary">
                                            Editar
                                        </a>


                                        <form
                                            action="{{ route('admin.tipos-entradas.estado', $tipo) }}"
                                            method="POST" class="d-inline">

                                            @csrf
                                            @method('PATCH')

                                            @if ($tipo->activo)
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="
                                                    return confirm(
                                                        '¿Deseas desactivar esta entrada?'
                                                    );
                                                ">
                                                    Desactivar
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    Activar
                                                </button>
                                            @endif

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No existen tipos de entradas registrados.
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection