@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4 p-md-5">


                        {{-- ENCABEZADO --}}
                        <div class="text-center">

                            <i class="bi bi-check-circle-fill text-success" style="font-size: 64px;">
                            </i>

                            <h2 class="mt-3 fw-bold">
                                ¡Pago realizado!
                            </h2>

                            <p class="text-muted mb-0">
                                Tu ticket vehicular fue pagado correctamente.
                            </p>

                        </div>


                        <hr class="my-4">


                        {{-- DATOS DE LA COMPRA --}}
                        <div class="row g-4">

                            <div class="col-md-6">

                                <strong>
                                    Nombre
                                </strong>

                                <div>
                                    {{ $venta->nombre_cliente }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <strong>
                                    RUT
                                </strong>

                                <div>
                                    {{ $venta->rut_cliente }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <strong>
                                    Correo
                                </strong>

                                <div>
                                    {{ $venta->correo }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <strong>
                                    Acompañantes
                                </strong>

                                <div>
                                    {{ $venta->cantidad_personas }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <strong>
                                    Total pagado
                                </strong>

                                <div>
                                    ${{ number_format($venta->total, 0, ',', '.') }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <strong>
                                    Orden de compra
                                </strong>

                                <div>
                                    {{ $venta->webpay_buy_order }}
                                </div>

                            </div>


                            @if (!empty($venta->authorization_code))
                                <div class="col-md-6">

                                    <strong>
                                        Código autorización
                                    </strong>

                                    <div>
                                        {{ $venta->authorization_code }}
                                    </div>

                                </div>
                            @endif


                            @if ($venta->pagada_at)
                                <div class="col-md-6">

                                    <strong>
                                        Fecha de emisión
                                    </strong>

                                    <div>
                                        {{ $venta->pagada_at->format('d/m/Y H:i') }}
                                    </div>

                                </div>
                            @endif

                        </div>


                        {{-- MENSAJE --}}
                        <div class="alert alert-success mt-4 mb-0">

                            <div class="d-flex gap-3">

                                <div>
                                    <i class="bi bi-envelope-check fs-4"></i>
                                </div>

                                <div>

                                    <strong>
                                        ¡Todo listo!
                                    </strong>

                                    <div class="mt-1">

                                        Hemos enviado tu ticket vehicular al correo:

                                        <strong>
                                            {{ $venta->correo }}
                                        </strong>

                                    </div>


                                    <div class="mt-3">

                                        El ticket tiene una vigencia de

                                        <strong>
                                            3 meses desde su emisión
                                        </strong>

                                        y puede ser utilizado una sola vez.

                                    </div>


                                    <div class="mt-2">

                                        También podrás consultar tus tickets
                                        desde tu cuenta.

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ACCIONES --}}
                        <div class="row g-2 mt-4">


                            {{-- MIS TICKETS --}}
                            <div class="col-12 col-md-6">

                                <a href="{{ route('tickets.index') }}" class="btn btn-outline-success w-100 py-2">

                                    <i class="bi bi-ticket-perforated me-2"></i>

                                    Ver mis tickets

                                </a>

                            </div>


                            {{-- FINALIZAR --}}
                            <div class="col-12 col-md-6">

                                <a href="{{ route('ventas.create') }}" class="btn btn-success w-100 py-2">

                                    <i class="bi bi-check2-circle me-2"></i>

                                    Finalizar

                                </a>

                            </div>


                        </div>


                        {{-- TEXTO INFERIOR --}}
                        <div class="text-center text-muted small mt-4">

                            <i class="bi bi-shield-check me-1"></i>

                            Presenta el código QR de tu ticket
                            al momento de ingresar al parque.

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection