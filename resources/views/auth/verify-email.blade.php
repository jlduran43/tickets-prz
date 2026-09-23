@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="card shadow-sm border-0 mx-auto" style="max-width: 520px; border-radius: 20px;">

            <div class="card-body p-4 p-md-5 text-center">

                <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    background: #e8f5ee;
                    color: #198754;
                    font-size: 32px;
                ">
                    <i class="bi bi-envelope-check"></i>
                </div>

                <h2 class="fw-bold mb-3" style="color:#14532d;">
                    Revisa tu correo
                </h2>

                <p class="text-muted mb-3">
                    Te enviamos un enlace de verificación a:
                </p>

                <p class="fw-bold text-success mb-4">
                    {{ auth()->user()->email }}
                </p>

                <p class="text-muted">
                    Debes verificar tu correo electrónico
                    antes de continuar con la compra de tickets.
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-3">

                        Te enviamos un nuevo enlace de verificación.

                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                    @csrf

                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2">
                        <i class="bi bi-envelope me-2"></i>
                        Reenviar correo de verificación
                    </button>

                </form>

            </div>

        </div>

    </div>
@endsection