<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tickets PRZ')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =====================================
           NAVBAR USUARIO / PERFIL
        ====================================== */

        .navbar-user-dropdown .dropdown-toggle {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .5rem .75rem;
            font-size: 1rem;
            font-weight: 500;
            color: #212529 !important;
            border: 0;
            background: transparent;
            box-shadow: none !important;
        }

        .navbar-user-dropdown .dropdown-toggle:hover,
        .navbar-user-dropdown .dropdown-toggle:focus {
            color: #198754 !important;
            background: #f8f9fa;
            border-radius: .5rem;
        }

        .navbar-user-dropdown .dropdown-menu {
            min-width: 190px;
            border: 0;
            border-radius: .75rem;
            padding: .5rem;
        }

        .navbar-user-dropdown .dropdown-item {
            border-radius: .5rem;
            padding: .6rem .75rem;
        }

        .navbar-user-dropdown .dropdown-item:hover {
            background: #f8f9fa;
        }

        @@media (max-width: 991.98px) {

            .navbar-user-dropdown {
                margin-top: .75rem;
                padding-top: .75rem;
                border-top: 1px solid #dee2e6;
            }

            .navbar-user-dropdown .dropdown-menu {
                position: static !important;
                transform: none !important;
                box-shadow: none !important;
                border: 1px solid #dee2e6;
                margin-top: .35rem;
            }
        }
    </style>

</head>

<body class="bg-light">

    @auth

        @if (
            (auth()->user()->rol !== 'CLIENTE' || auth()->user()->hasVerifiedEmail()) &&
                !request()->routeIs('verification.success'))
            <nav class="navbar navbar-expand-lg bg-white shadow-sm py-2">

                <div class="container">

                    {{-- =====================================
                        MARCA
                    ====================================== --}}

                    @php
                        $inicio = match (auth()->user()->rol) {
                            'ADMIN' => route('admin.usuarios.index'),
                            'CONTROL' => route('control.index'),
                            default => route('ventas.create'),
                        };
                    @endphp

                    <a class="navbar-brand fw-bold text-success me-lg-4" href="{{ $inicio }}">
                        Tickets PRZ
                    </a>


                    {{-- =====================================
                        BOTÓN HAMBURGUESA
                    ====================================== --}}

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrz"
                        aria-controls="navbarPrz" aria-expanded="false" aria-label="Abrir menú">
                        <span class="navbar-toggler-icon"></span>
                    </button>


                    {{-- =====================================
                        CONTENIDO NAVBAR
                    ====================================== --}}

                    <div class="collapse navbar-collapse" id="navbarPrz">

                        {{-- =================================
                            MENÚ PRINCIPAL
                        ================================== --}}

                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">


                            {{-- =============================
                                CLIENTE
                            ============================== --}}

                            @if (auth()->user()->rol === 'CLIENTE')
                                <li class="nav-item">

                                    <a href="{{ route('ventas.create') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('ventas.create') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-cart-plus me-1"></i>

                                        Comprar ticket

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a href="{{ route('tickets.index') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('tickets.*') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-ticket-perforated me-1"></i>

                                        Mis tickets

                                    </a>

                                </li>
                            @endif


                            {{-- =============================
                                CONTROL
                            ============================== --}}

                            @if (auth()->user()->rol === 'CONTROL')
                                <li class="nav-item">

                                    <a href="{{ route('control.index') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('control.index') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-house-door me-1"></i>

                                        Inicio

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a href="{{ route('control.scanner') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('control.scanner') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-qr-code-scan me-1"></i>

                                        Escanear ticket

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a href="{{ route('control.historial') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('control.historial') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-clock-history me-1"></i>

                                        Historial

                                    </a>

                                </li>
                            @endif


                            {{-- =============================
                                ADMIN
                            ============================== --}}

                            @if (auth()->user()->rol === 'ADMIN')
                                <li class="nav-item">

                                    <a href="{{ route('admin.usuarios.index') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('admin.usuarios.*') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-people me-1"></i>

                                        Usuarios

                                    </a>

                                </li>


                                <li class="nav-item">

                                    <a href="{{ route('admin.tipos-entradas.index') }}"
                                        class="
                                            nav-link
                                            px-lg-3
                                            {{ request()->routeIs('admin.tipos-entradas.*') ? 'active fw-semibold text-success' : '' }}
                                        ">

                                        <i class="bi bi-ticket-perforated me-1"></i>

                                        Tipos de entradas

                                    </a>

                                </li>
                            @endif

                        </ul>


                        {{-- =================================
                            USUARIO / PERFIL
                        ================================== --}}

                        <div class="navbar-user-dropdown dropdown ms-lg-auto">

                            <button class="btn btn-link text-dark text-decoration-none dropdown-toggle" type="button"
                                id="menuUsuario" data-bs-toggle="dropdown" aria-expanded="false">

                                <i class="bi bi-person-circle me-1"></i>

                                {{ auth()->user()->name }}

                            </button>


                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="menuUsuario">

                                <li>

                                    <a class="dropdown-item" href="{{ route('perfil.edit') }}">

                                        <i class="bi bi-person-gear me-2"></i>

                                        Mi perfil

                                    </a>

                                </li>


                                <li>
                                    <hr class="dropdown-divider">
                                </li>


                                <li>

                                    <form method="POST" action="{{ route('logout') }}">

                                        @csrf

                                        <button type="submit" class="dropdown-item text-danger">

                                            <i class="bi bi-box-arrow-right me-2"></i>

                                            Cerrar sesión

                                        </button>

                                    </form>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </nav>
        @endif

    @endauth


    {{-- =====================================
        CONTENIDO
    ====================================== --}}

    <main class="container py-4">

        @yield('content')

    </main>


    {{-- =====================================
        JS EXTRA
    ====================================== --}}

    @yield('js')

</body>

</html>