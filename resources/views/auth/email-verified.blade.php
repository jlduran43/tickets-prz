@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/email-verified.css') }}">

    <div class="verified-page">

        <div class="verified-card">

            <div class="verified-icon">

                <i class="bi bi-check-lg"></i>

            </div>

            <h1 class="verified-title">¡Correo verificado!</h1>

            <p class="verified-text">
                Tu correo electrónico fue verificado correctamente.
                <br>
                Ya puedes comprar tus tickets.
            </p>

            <div class="redirect-box">

                <div class="mb-2">

                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>

                </div>

                <div>
                    Te redirigiremos en
                    <span id="contador" data-redirect="{{ $redirectUrl }}">3</span>
                    segundos...
                </div>

            </div>

            <a href="{{ $redirectUrl }}" class="btn-ir">
                <i class="bi bi-cart"></i>
                Ir a comprar ticket ahora
            </a>

        </div>

    </div>
@endsection

@section('js')
    <script src="{{ asset('js/email-verified.js') }}v=3" defer></script>
@endsection
