@extends('layouts.app')

@section('title', 'Control de acceso')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-9">

                {{-- CABECERA --}}
                <div class="mb-4">

                    <h2 class="fw-bold mb-1">
                        Control de acceso
                    </h2>

                    <p class="text-muted mb-0">
                        Escanea y valida los tickets vehiculares.
                    </p>

                </div>


                <div class="row g-4">

                    {{-- ESCÁNER --}}
                    <div class="col-md-6">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body p-4 text-center">

                                <i class="bi bi-qr-code-scan text-success" style="font-size: 64px;">
                                </i>

                                <h4 class="fw-bold mt-3">
                                    Escanear ticket
                                </h4>

                                <p class="text-muted">
                                    Utiliza la cámara para leer el código QR
                                    presentado por el visitante.
                                </p>

                                <a href="{{ route('control.scanner') }}" class="btn btn-success px-4">

                                    <i class="bi bi-camera me-2"></i>

                                    Abrir escáner

                                </a>

                            </div>

                        </div>

                    </div>


                    {{-- VALIDACIONES --}}
                    <div class="col-md-6">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body p-4 text-center">

                                <i class="bi bi-clock-history text-success" style="font-size: 64px;">
                                </i>

                                <h4 class="fw-bold mt-3">
                                    Últimas validaciones
                                </h4>

                                <p class="text-muted">
                                    Consulta los tickets validados recientemente.
                                </p>

                                <a href="{{ route('control.historial') }}" class="btn btn-outline-success px-4">

                                    <i class="bi bi-list-check me-2"></i>

                                    Ver historial

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection