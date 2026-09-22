@extends('layouts.app')

@section('title', 'Nueva venta')

@section('content')

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card shadow-sm">

                @if ($errors->any())

                    <div class="alert alert-danger">

                        <strong>
                            No se pudo continuar:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>

                @endif

                <div class="card-header">
                    <h4 class="mb-0">
                        Compra tu ticket de acceso
                    </h4>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            Hola,
                            <strong>{{ auth()->user()->name }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('ventas.store') }}" method="POST">

                        @csrf

                        <h5 class="mb-3">Datos de la persona</h5>

                        <div class="row g-3 mb-4">

                            {{-- Nombre --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nombre completo
                                </label>

                                <input type="text" name="nombre_cliente" class="form-control"
                                    value="{{ old('nombre_cliente', auth()->check() ? auth()->user()->name : '') }}"
                                    readonly required>
                            </div>

                            {{-- RUT --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    RUT
                                </label>

                                <input type="text" name="rut_cliente" id="rut_cliente"
                                    class="form-control @error('rut_cliente') is-invalid @enderror"
                                    value="{{ old('rut_cliente', $cliente?->rut) }}" readonly required>

                                @error('rut_cliente')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Correo --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Correo electrónico
                                </label>

                                <input type="email" name="correo" id="correo"
                                    class="form-control @error('correo') is-invalid @enderror"
                                    value="{{ old('correo', auth()->check() ? auth()->user()->email : '') }}" readonly
                                    required>

                                @error('correo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Teléfono
                                </label>

                                <input type="text" name="telefono" class="form-control"
                                    value="{{ old('telefono', $cliente?->telefono) }}" readonly required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Región
                                </label>

                                <select name="region_id" id="region_id" class="form-select" required>
                                    <option value="">
                                        Seleccione región...
                                    </option>

                                    @foreach ($regiones as $region)
                                        <option value="{{ $region->id }}"
                                            {{ old('region_id', $cliente?->region_id) == $region->id ? 'selected' : '' }}>
                                            {{ $region->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Comuna
                                </label>

                                <select name="comuna_id" id="comuna" class="form-select" required disabled>
                                    <option value="">
                                        Seleccione primero una región...
                                    </option>
                                </select>
                            </div>

                            {{-- Cantidad personas --}}
                            <div class="mb-3">

                                <label for="cantidad_personas" class="form-label">
                                    Cantidad de acompañantes
                                </label>

                                <input type="number" id="cantidad_personas" name="cantidad_personas"
                                    class="form-control @error('cantidad_personas') is-invalid @enderror" min="0"
                                    value="{{ old('cantidad_personas') }}" placeholder="Ej: 3" required>

                                <div class="form-text">
                                    Indica cuántas personas te acompañarán en el vehículo.
                                </div>

                                @error('cantidad_personas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="alert alert-light border shadow-sm mb-4" role="alert">

                            <h5 class="fw-bold mb-3">
                                Ticket de Acceso Vehicular Web
                            </h5>

                            <p class="mb-2">
                                Disfruta tu visita al
                                <strong>Parque Museo Pedro del Río Zañartu</strong>
                                y recorre sus espacios naturales y patrimoniales.
                            </p>

                            <p class="mb-2">
                                El Parque se encuentra abierto para el ingreso vehicular de
                                <strong>9:00 a 19:30 horas</strong>.
                            </p>

                            <p class="mb-2">
                                El <strong>Ticket Vehicular Web</strong> tiene una vigencia de
                                <strong>3 meses desde su fecha de emisión</strong>
                                y permite <strong>un único ingreso al Parque para un automóvil</strong>
                                durante dicho período.
                            </p>

                            <p class="mb-0">
                                El ticket es válido exclusivamente para vehículos particulares
                                y debe ser presentado al momento de ingresar al recinto.
                            </p>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Entrada</label>

                            <div class="form-control bg-light">
                                Entrada vehículo - $3.000
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <label class="form-label d-block mb-3">
                                Medio de pago
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="medio_pago" value="WEBPAY" checked>

                                <div class="payment-card">
                                    <div class="payment-icon">
                                        <img src="{{ asset('images/webpay.png') }}" alt="Webpay" class="webpay-logo">
                                    </div>

                                    <div>
                                        <small class="text-muted">
                                            Paga con débito o crédito
                                        </small>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="text-end">

                            <button type="submit" class="btn btn-success btn-lg">
                                Pagar
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const regionSelect = document.getElementById('region_id');
            const comunaSelect = document.getElementById('comuna');

            const comunaAnterior = @json(old('comuna_id', $cliente?->comuna_id));

            function cargarComunas(regionId, comunaSeleccionada = null) {

                comunaSelect.innerHTML =
                    '<option value="">Cargando comunas...</option>';

                comunaSelect.disabled = true;

                if (!regionId) {

                    comunaSelect.innerHTML =
                        '<option value="">Seleccione primero una región...</option>';

                    return;
                }

                fetch(`/comunas/${regionId}`)
                    .then(response => {

                        if (!response.ok) {
                            throw new Error('Error al cargar comunas');
                        }

                        return response.json();
                    })
                    .then(comunas => {

                        comunaSelect.innerHTML =
                            '<option value="">Seleccione comuna...</option>';

                        comunas.forEach(comuna => {

                            const option =
                                document.createElement('option');

                            option.value = comuna.id;
                            option.textContent = comuna.nombre;

                            if (
                                comunaSeleccionada &&
                                comuna.id == comunaSeleccionada
                            ) {
                                option.selected = true;
                            }

                            comunaSelect.appendChild(option);
                        });

                        comunaSelect.disabled = false;

                    })
                    .catch(error => {

                        console.error(
                            'Error cargando comunas:',
                            error
                        );

                        comunaSelect.innerHTML =
                            '<option value="">Error al cargar comunas</option>';
                    });
            }


            /*
            |--------------------------------------------------------------------------
            | Cuando cambia la región
            |--------------------------------------------------------------------------
            */

            regionSelect.addEventListener(
                'change',
                function() {

                    cargarComunas(this.value);

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Al cargar la página
            |--------------------------------------------------------------------------
            |
            | Si el cliente ya tiene una región guardada,
            | cargar automáticamente sus comunas y seleccionar
            | su comuna registrada.
            |
            */

            if (regionSelect.value) {

                cargarComunas(
                    regionSelect.value,
                    comunaAnterior
                );

            }

        });
    </script>
@endsection
