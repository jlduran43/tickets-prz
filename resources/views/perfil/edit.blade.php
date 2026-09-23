@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')

    <style>
        .profile-page {
            max-width: 860px;
            margin: 0 auto;
        }

        .profile-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 36px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e6f5ed;
            color: #198754;
            font-size: 32px;
        }

        .profile-title {
            color: #14532d;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .profile-subtitle {
            color: #6c757d;
            margin: 0;
        }

        .form-label {
            color: #234736;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            height: 52px;
            border-radius: 12px;
            border: 1px solid #d7e1db;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3f8f69;
            box-shadow: 0 0 0 0.2rem rgba(63, 143, 105, 0.15);
        }

        .input-icon {
            position: relative;
        }

        .input-icon>i {
            position: absolute;
            left: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: #64756d;
            font-size: 18px;
            pointer-events: none;
            z-index: 5;
        }

        .input-icon .form-control {
            padding-left: 48px;
        }

        .optional-label {
            color: #8b9690;
            font-size: 0.78rem;
            font-weight: 500;
            margin-left: 5px;
        }

        .news-box {
            margin-top: 10px;
            margin-bottom: 25px;
            padding: 18px 20px;
            border: 1px solid #d7e6dd;
            border-radius: 15px;
            background: #f4f9f6;
        }

        .news-title {
            color: #176341;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .news-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .btn-profile {
            width: 100%;
            min-height: 50px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, #188a55, #219b62);
            color: #ffffff;
            font-weight: 700;
            transition: all .2s ease;
        }

        .btn-profile:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #147547, #198754);
            transform: translateY(-1px);
        }

        @media (max-width: 576px) {
            .profile-card {
                padding: 24px 18px;
            }

            .profile-title {
                font-size: 1.7rem;
            }

            .news-box .d-flex {
                flex-direction: column;
                gap: 8px !important;
            }
        }
    </style>

    <div class="profile-page">

        <div class="profile-card">

            <div class="profile-header">

                <div class="profile-icon">
                    <i class="bi bi-person-gear"></i>
                </div>

                <h1 class="profile-title">
                    Mi perfil
                </h1>

                <p class="profile-subtitle">
                    Actualiza tus datos personales y preferencias.
                </p>

            </div>


            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif


            <form method="POST" action="{{ route('perfil.update') }}">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- NOMBRE --}}
                    <div class="col-md-6 mb-3">

                        <label for="name" class="form-label">
                            Nombre
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-person"></i>

                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>

                        </div>

                        @error('name')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- TELÉFONO --}}
                    <div class="col-md-6 mb-3">

                        <label for="telefono" class="form-label">
                            Teléfono
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-telephone"></i>

                            <input type="text" id="telefono" name="telefono" class="form-control"
                                value="{{ old('telefono', $cliente->telefono) }}" placeholder="+56 9 1234 5678" required>

                        </div>

                        @error('telefono')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- REGIÓN --}}
                    <div class="col-md-6 mb-3">

                        <label for="region_id" class="form-label">
                            Región
                        </label>

                        <select name="region_id" id="region_id" class="form-select" required>

                            <option value="">
                                Seleccione una región
                            </option>

                            @foreach ($regiones as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('region_id', $cliente->region_id) == $region->id ? 'selected' : '' }}>
                                    {{ $region->nombre }}
                                </option>
                            @endforeach

                        </select>

                        @error('region_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- COMUNA --}}
                    <div class="col-md-6 mb-3">

                        <label for="comuna_id" class="form-label">
                            Comuna
                        </label>

                        <select name="comuna_id" id="comuna_id" class="form-select" required>

                            <option value="">
                                Seleccione una comuna
                            </option>

                            @foreach ($comunas as $comuna)
                                <option value="{{ $comuna->id }}"
                                    {{ old('comuna_id', $cliente->comuna_id) == $comuna->id ? 'selected' : '' }}>
                                    {{ $comuna->nombre }}
                                </option>
                            @endforeach

                        </select>

                        @error('comuna_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- DIRECCIÓN --}}
                    <div class="col-md-6 mb-3">

                        <label for="direccion" class="form-label">
                            Dirección
                            <span class="optional-label">
                                Opcional
                            </span>
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-geo-alt"></i>

                            <input type="text" id="direccion" name="direccion" class="form-control"
                                value="{{ old('direccion', $cliente->direccion) }}" placeholder="Ej: Los Carrera 1234">

                        </div>

                        @error('direccion')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- PATENTE --}}
                    <div class="col-md-6 mb-3">

                        <label for="patente" class="form-label">
                            Patente del vehículo
                            <span class="optional-label">
                                Opcional
                            </span>
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-car-front"></i>

                            <input type="text" id="patente" name="patente" class="form-control text-uppercase"
                                value="{{ old('patente', $cliente->patente) }}" placeholder="ABCD12" maxlength="20">

                        </div>

                        @error('patente')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- NOTICIAS --}}
                <div class="news-box">

                    <div class="news-title">

                        <i class="bi bi-envelope-heart me-2"></i>

                        Noticias y novedades

                    </div>

                    <div class="news-description">

                        ¿Quieres recibir correos con noticias,
                        actividades y novedades del Parque Museo?

                    </div>


                    <div class="d-flex gap-4">

                        <div class="form-check">

                            <input class="form-check-input" type="radio" name="recibir_noticias" id="noticiasSi"
                                value="1"
                                {{ old('recibir_noticias', $cliente->recibir_noticias) == 1 ? 'checked' : '' }}>

                            <label class="form-check-label" for="noticiasSi">
                                Sí, deseo recibir noticias
                            </label>

                        </div>


                        <div class="form-check">

                            <input class="form-check-input" type="radio" name="recibir_noticias" id="noticiasNo"
                                value="0"
                                {{ old('recibir_noticias', $cliente->recibir_noticias) == 0 ? 'checked' : '' }}>

                            <label class="form-check-label" for="noticiasNo">
                                No, gracias
                            </label>

                        </div>

                    </div>

                </div>


                <button type="submit" class="btn-profile">

                    <i class="bi bi-check-circle me-2"></i>

                    Guardar cambios

                </button>

            </form>

        </div>

    </div>

@endsection


@section('js')

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const regionSelect =
                    document.getElementById('region_id');

                const comunaSelect =
                    document.getElementById('comuna_id');


                if (!regionSelect || !comunaSelect) {
                    return;
                }


                regionSelect.addEventListener(
                    'change',
                    async function() {

                        const regionId = this.value;

                        comunaSelect.innerHTML =
                            '<option value="">Cargando comunas...</option>';


                        if (!regionId) {

                            comunaSelect.innerHTML =
                                '<option value="">Seleccione una comuna</option>';

                            return;
                        }


                        try {

                            const response =
                                await fetch(
                                    `/comunas/${regionId}`
                                );


                            if (!response.ok) {
                                throw new Error(
                                    'No se pudieron cargar las comunas.'
                                );
                            }


                            const comunas =
                                await response.json();


                            comunaSelect.innerHTML =
                                '<option value="">Seleccione una comuna</option>';


                            comunas.forEach(
                                function(comuna) {

                                    const option =
                                        document.createElement(
                                            'option'
                                        );

                                    option.value =
                                        comuna.id;

                                    option.textContent =
                                        comuna.nombre;

                                    comunaSelect.appendChild(
                                        option
                                    );

                                }
                            );

                        } catch (error) {

                            console.error(error);

                            comunaSelect.innerHTML =
                                '<option value="">Error al cargar comunas</option>';

                        }

                    }
                );

            }
        );
    </script>

@endsection