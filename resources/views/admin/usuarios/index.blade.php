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

                                        <th class="px-4 py-3">Nombre</th>
                                        <th class="py-3">Correo</th>
                                        <th class="py-3">Rol</th>
                                        <th class="py-3">Fecha creación</th>
                                        <th class="py-3">Estado</th>
                                        <th class="py-3 text-center">Acciones</th>

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
                                            <td>{{ $usuario->email }}</td>

                                            {{-- ROL --}}
                                            <td>

                                                @if ($usuario->rol === 'ADMIN')
                                                    <span
                                                        class="badge bg-dark 
                                                                rounded-pill px-3 py-2">
                                                        Administrador
                                                    </span>
                                                @elseif($usuario->rol === 'CONTROL')
                                                    <span
                                                        class="badge bg-success 
                                                                rounded-pill px-3 py-2">
                                                        Control
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary 
                                                                rounded-pill px-3 py-2">
                                                        Cliente
                                                    </span>
                                                @endif

                                            </td>

                                            {{-- FECHA --}}
                                            <td>
                                                {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : '-' }}
                                            </td>

                                            {{-- ESTADO --}}
                                            <td>

                                                @if ($usuario->activo)
                                                    <span
                                                        class="badge bg-success 
                                                                rounded-pill px-3 py-2">

                                                        <i class="bi bi-check-circle me-1"></i>

                                                        Activo

                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-danger 
                                                                rounded-pill px-3 py-2">

                                                        <i class="bi bi-x-circle me-1"></i>

                                                        Desactivado

                                                    </span>
                                                @endif

                                            </td>

                                            {{-- ACCIONES --}}
                                            <td class="text-center">

                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                                        class="btn btn-warning btn-sm px-3 py-2 rounded-2 d-inline-flex align-items-center gap-1">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Editar
                                                    </a>

                                                    <button type="button"
                                                        class="btn btn-sm
                                                            {{ $usuario->activo ? 'btn-danger' : 'btn-success' }}
                                                            btn-accion-usuario"
                                                        data-bs-toggle="modal" data-bs-target="#modalEstadoUsuario"
                                                        data-url="{{ route('admin.usuarios.estado', $usuario) }}"
                                                        data-nombre="{{ $usuario->name }}"
                                                        data-activo="{{ $usuario->activo ? 1 : 0 }}">

                                                        @if ($usuario->activo)
                                                            <i class="bi bi-person-x me-1"></i>
                                                            Desactivar
                                                        @else
                                                            <i class="bi bi-person-check me-1"></i>
                                                            Activar
                                                        @endif

                                                    </button>

                                                </div>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="5" class="text-center py-5 text-muted">

                                                <i class="bi bi-people" style="font-size: 42px;"></i>

                                                <div class="mt-2">
                                                    No hay usuarios registrados.
                                                </div>

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        {{-- PAGINACIÓN --}}
                        @if ($usuarios->hasPages())
                            <div    class="d-flex flex-column flex-md-row
                                    justify-content-between align-items-center
                                    gap-3 px-4 py-3 border-top">

                                <div class="text-muted small">
                                    Mostrando
                                    <strong>{{ $usuarios->firstItem() }}</strong>
                                    a
                                    <strong>{{ $usuarios->lastItem() }}</strong>
                                    de
                                    <strong>{{ $usuarios->total() }}</strong>
                                    usuarios
                                </div>

                                <div>
                                    {{ $usuarios->links('pagination::bootstrap-5') }}
                                </div>

                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL ACTIVAR / DESACTIVAR USUARIO --}}
    <div class="modal fade" id="modalEstadoUsuario" tabindex="-1" aria-labelledby="modalEstadoUsuarioLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalEstadoUsuarioLabel">
                        Cambiar estado de usuario
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="text-center mb-3">
                        <i id="iconoEstadoUsuario" class="bi" style="font-size: 48px;">
                        </i>
                    </div>

                    <p class="text-center mb-2" id="mensajeEstadoUsuario"></p>

                    <p class="text-center fw-bold">
                        <span id="nombreEstadoUsuario"></span>
                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <form id="formEstadoUsuario" method="POST">

                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn" id="btnConfirmarEstado">
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

@section('js')
    <script src="{{ asset('js/usuarios-index.js') }}"></script>
@endsection
