@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                {{-- CABECERA --}}
                <div
                    class="d-flex flex-column flex-md-row
                        justify-content-between
                        align-items-md-center
                        gap-3 mb-4">

                    <div>

                        <h2 class="fw-bold mb-1">
                            Usuarios
                        </h2>

                        <p class="text-muted mb-0">
                            Administración de usuarios del sistema.
                        </p>

                    </div>

                    <div>

                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success">

                            <i class="bi bi-person-plus me-2"></i>

                            Crear usuario

                        </a>

                    </div>

                </div>


                {{-- MENSAJE DE ÉXITO --}}
                @if (session('success'))
                    <div class="alert alert-success">

                        <i class="bi bi-check-circle me-2"></i>

                        {{ session('success') }}

                    </div>
                @endif


                {{-- TABLA --}}
                <div class="card shadow-sm border-0">

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="px-4 py-3">
                                            Nombre
                                        </th>

                                        <th class="py-3">
                                            Correo
                                        </th>

                                        <th class="py-3">
                                            Rol
                                        </th>

                                        <th class="py-3">
                                            Fecha creación
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($usuarios as $usuario)
                                        <tr>

                                            {{-- NOMBRE --}}
                                            <td class="px-4 py-3">

                                                <div class="d-flex align-items-center gap-2">

                                                    <div class="rounded-circle bg-light
                                                           d-flex align-items-center
                                                           justify-content-center"
                                                        style="width: 38px; height: 38px;">

                                                        <i class="bi bi-person text-success"></i>

                                                    </div>

                                                    <div class="fw-semibold">

                                                        {{ $usuario->name ?? 'Sin nombre' }}

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- CORREO --}}
                                            <td>

                                                {{ $usuario->email }}

                                            </td>


                                            {{-- ROL --}}
                                            <td>

                                                @if ($usuario->rol === 'ADMIN')
                                                    <span class="badge bg-dark rounded-pill px-3 py-2">
                                                        Administrador
                                                    </span>
                                                @elseif($usuario->rol === 'CONTROL')
                                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                                        Control
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                        Cliente
                                                    </span>
                                                @endif

                                            </td>


                                            {{-- FECHA --}}
                                            <td>

                                                {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : '-' }}

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4" class="text-center py-5 text-muted">

                                                <i class="bi bi-people" style="font-size: 42px;">
                                                </i>

                                                <div class="mt-2">
                                                    No hay usuarios registrados.
                                                </div>

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection