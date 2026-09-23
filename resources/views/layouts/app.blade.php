<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Tickets PRZ')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-light">

    @auth

        @if (auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.success'))

        <nav class="navbar bg-white shadow-sm py-3">

            <div class="container position-relative">

                <div class="d-flex align-items-center justify-content-between w-100">

                    {{-- MARCA --}}
                    <a class="navbar-brand fw-semibold text-success mb-0"
                        href="
                            @if (auth()->user()->rol === 'ADMIN') {{ route('admin.usuarios.index') }}
                            @elseif(auth()->user()->rol === 'CONTROL')
                                {{ route('control.index') }}
                            @else
                                {{ route('ventas.create') }} @endif
                        ">
                        Tickets PRZ
                    </a>


                    {{-- BOTÓN MÓVIL --}}
                    <button type="button" class="btn btn-outline-secondary d-lg-none" id="btnMenuMovil"
                        aria-label="Abrir menú">

                        <i class="bi bi-list fs-3"></i>

                    </button>

                </div>


                {{-- CONTENIDO DEL MENÚ --}}
                <div id="menuPrz" class="menu-prz-custom w-100 mt-3 mt-lg-0">

                    <div class="d-lg-flex align-items-lg-center w-100">


                        {{-- MENÚ PRINCIPAL --}}
                        <div class="d-lg-flex align-items-lg-center">


                            {{-- ================================================= --}}
                            {{-- CLIENTE --}}
                            {{-- ================================================= --}}

                            @if (auth()->user()->rol === 'CLIENTE')
                                <a href="{{ route('ventas.create') }}"
                                    class="nav-link px-lg-3 py-2
                                    {{ request()->routeIs('ventas.create') ? 'fw-semibold text-success' : 'text-dark' }}">

                                    <i class="bi bi-cart-plus me-1"></i>

                                    Comprar ticket

                                </a>


                                <a href="{{ route('tickets.index') }}"
                                    class="nav-link px-lg-3 py-2
                                    {{ request()->routeIs('tickets.*') ? 'fw-semibold text-success' : 'text-dark' }}">

                                    <i class="bi bi-ticket-perforated me-1"></i>

                                    Mis tickets

                                </a>
                            @endif



                            {{-- ================================================= --}}
                            {{-- CONTROL --}}
                            {{-- ================================================= --}}

                            @if (auth()->user()->rol === 'CONTROL')
                                <a href="{{ route('control.index') }}"
                                    class="nav-link px-lg-3 py-2
                                    {{ request()->routeIs('control.index') ? 'fw-semibold text-success' : 'text-dark' }}">

                                    <i class="bi bi-house-door me-1"></i>

                                    Inicio

                                </a>


                                <a href="{{ route('control.scanner') }}"
                                    class="nav-link px-lg-3 py-2
                                    {{ request()->routeIs('control.scanner') ? 'fw-semibold text-success' : 'text-dark' }}">

                                    <i class="bi bi-qr-code-scan me-1"></i>

                                    Escanear ticket

                                </a>


                                <a href="{{ route('control.historial') }}"
                                    class="nav-link px-lg-3 py-2
                                    {{ request()->routeIs('control.historial') ? 'fw-semibold text-success' : 'text-dark' }}">

                                    <i class="bi bi-clock-history me-1"></i>

                                    Historial

                                </a>
                            @endif



                            {{-- ================================================= --}}
                            {{-- ADMIN --}}
                            {{-- ================================================= --}}

                            @if (auth()->user()->rol === 'ADMIN')
                                <a href="{{ route('admin.usuarios.index') }}"
                                    class="nav-link px-lg-3 py-2
                                    {{ request()->routeIs('admin.usuarios.*') ? 'fw-semibold text-success' : 'text-dark' }}">

                                    <i class="bi bi-people me-1"></i>

                                    Usuarios

                                </a>
                            @endif


                        </div>



                        {{-- USUARIO --}}
                        <div class="dropdown ms-lg-auto mt-2 mt-lg-0">

                            <button class="btn btn-light dropdown-toggle w-100 text-start" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">

                                <i class="bi bi-person-circle me-1"></i>

                                {{ auth()->user()->name ?? auth()->user()->email }}

                            </button>


                            <ul class="dropdown-menu dropdown-menu-end">


                                {{-- OPCIONES CLIENTE --}}
                                @if (auth()->user()->rol === 'CLIENTE')
                                    <li>

                                        <a class="dropdown-item" href="{{ route('tickets.index') }}">

                                            <i class="bi bi-ticket-perforated me-2"></i>

                                            Mis tickets

                                        </a>

                                    </li>
                                @endif



                                {{-- OPCIONES CONTROL --}}
                                @if (auth()->user()->rol === 'CONTROL')
                                    <li>

                                        <a class="dropdown-item" href="{{ route('control.scanner') }}">

                                            <i class="bi bi-qr-code-scan me-2"></i>

                                            Escanear ticket

                                        </a>

                                    </li>


                                    <li>

                                        <a class="dropdown-item" href="{{ route('control.historial') }}">

                                            <i class="bi bi-clock-history me-2"></i>

                                            Historial

                                        </a>

                                    </li>
                                @endif



                                {{-- OPCIONES ADMIN --}}
                                @if (auth()->user()->rol === 'ADMIN')
                                    <li>

                                        <a class="dropdown-item" href="{{ route('admin.usuarios.index') }}">

                                            <i class="bi bi-people me-2"></i>

                                            Usuarios

                                        </a>

                                    </li>
                                @endif



                                <li>
                                    <hr class="dropdown-divider">
                                </li>


                                {{-- CERRAR SESIÓN --}}
                                <li>

                                    <form method="POST" action="{{ route('logout') }}">

                                        @csrf

                                        <button type="submit" class="dropdown-item">

                                            <i class="bi bi-box-arrow-right me-2"></i>

                                            Cerrar sesión

                                        </button>

                                    </form>

                                </li>

                            </ul>

                        </div>


                    </div>

                </div>

            </div>

        </nav>

        @endif

    @endauth



    {{-- CONTENIDO --}}
    <main class="container py-4">

        @yield('content')

    </main>



    {{-- JS EXTRA DE CADA VISTA --}}
    @yield('js')



    {{-- MENÚ MÓVIL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const boton =
                document.getElementById('btnMenuMovil');

            const menu =
                document.getElementById('menuPrz');


            if (!boton || !menu) {
                return;
            }


            boton.addEventListener(
                'click',
                function(event) {

                    event.preventDefault();
                    event.stopPropagation();

                    menu.classList.toggle('menu-open');

                }
            );

        });
    </script>


</body>

</html>