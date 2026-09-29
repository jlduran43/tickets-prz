@extends('layouts.app')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow-sm">

                    <div class="card-header bg-white">

                        <h4 class="mb-0">
                            Editar usuario
                        </h4>

                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif


                        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">

                            @csrf
                            @method('PUT')


                            {{-- Nombre --}}

                            <div class="mb-3">

                                <label class="form-label">
                                    Nombre
                                </label>

                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $usuario->name) }}" required>

                            </div>


                            {{-- Email --}}

                            <div class="mb-3">

                                <label class="form-label">
                                    Correo electrónico
                                </label>

                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $usuario->email) }}" required>

                            </div>


                            {{-- Rol --}}

                            <div class="mb-3">

                                <label class="form-label">
                                    Rol
                                </label>

                                <select name="rol" class="form-select" required>

                                    <option value="ADMIN"
                                        {{ old('rol', $usuario->rol) === 'ADMIN' ? 'selected' : '' }}>
                                        Administrador
                                    </option>

                                    <option value="CONTROL"
                                        {{ old('rol', $usuario->rol) === 'CONTROL' ? 'selected' : '' }}>
                                        Control
                                    </option>

                                    <option value="CLIENTE"
                                        {{ old('rol', $usuario->rol) === 'CLIENTE' ? 'selected' : '' }}>
                                        Cliente
                                    </option>

                                </select>

                            </div>


                            <hr>


                            <h6>
                                Cambiar contraseña
                            </h6>

                            <p class="text-muted small">
                                Déjala en blanco si no deseas cambiarla.
                            </p>


                            <div class="mb-3">

                                <label class="form-label">
                                    Nueva contraseña
                                </label>

                                <input type="password" name="password" class="form-control">

                            </div>


                            <div class="mb-4">

                                <label class="form-label">
                                    Confirmar contraseña
                                </label>

                                <input type="password" name="password_confirmation" class="form-control">

                            </div>


                            <div class="d-flex justify-content-between">

                                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                                    Volver
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Guardar cambios
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection