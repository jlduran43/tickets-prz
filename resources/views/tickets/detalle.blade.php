@extends('layouts.app')

@section('title', 'Mi ticket')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-7">


                {{-- VOLVER --}}
                <div class="mb-3">

                    <a href="{{ route('tickets.index') }}" class="text-decoration-none text-success">

                        <i class="bi bi-arrow-left me-1"></i>

                        Volver a mis tickets

                    </a>

                </div>


                <div class="card shadow-sm border-0">

                    <div class="card-body p-4 p-md-5">


                        {{-- ENCABEZADO --}}
                        <div class="text-center">

                            <i class="bi bi-ticket-perforated text-success" style="font-size: 56px;">
                            </i>


                            <div class="text-muted mt-2">
                                Ticket vehicular
                            </div>


                            <h2 class="fw-bold mt-1 mb-3">

                                {{ $venta->folio }}

                            </h2>


                            @if ($estadoTicket === 'VIGENTE')
                                <span class="badge bg-success rounded-pill px-4 py-2">

                                    <i class="bi bi-check-circle me-1"></i>

                                    VIGENTE

                                </span>
                            @elseif($estadoTicket === 'UTILIZADO')
                                <span class="badge bg-secondary rounded-pill px-4 py-2">

                                    <i class="bi bi-check2-circle me-1"></i>

                                    UTILIZADO

                                </span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-4 py-2">

                                    <i class="bi bi-clock-history me-1"></i>

                                    VENCIDO

                                </span>
                            @endif

                        </div>



                        {{-- QR --}}
                        @if ($estadoTicket === 'VIGENTE' && $qrBase64)
                            <div class="text-center my-4">

                                <div class="mb-2 fw-semibold">

                                    Código QR de acceso

                                </div>


                                <div class="d-inline-block p-3 bg-white border rounded-4 shadow-sm">

                                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="Código QR del ticket"
                                        class="img-fluid" style="max-width: 280px;">

                                </div>


                                <p class="text-muted small mt-3 mb-0">

                                    Presenta este código QR al momento
                                    de ingresar al parque.

                                </p>


                                <p class="text-muted small mb-0">

                                    El código puede ser utilizado
                                    una sola vez.

                                </p>

                            </div>
                        @endif



                        {{-- UTILIZADO --}}
                        @if ($estadoTicket === 'UTILIZADO')
                            <div class="alert alert-secondary mt-4">

                                <div class="d-flex gap-2">

                                    <i class="bi bi-check2-circle fs-4"></i>

                                    <div>

                                        <strong>
                                            Ticket utilizado
                                        </strong>

                                        <div>

                                            Este ticket fue utilizado el

                                            <strong>
                                                {{ $venta->validada_at->format('d/m/Y H:i') }}
                                            </strong>.

                                        </div>

                                    </div>

                                </div>

                            </div>
                        @endif



                        {{-- VENCIDO --}}
                        @if ($estadoTicket === 'VENCIDO')
                            <div class="alert alert-warning mt-4">

                                <div class="d-flex gap-2">

                                    <i class="bi bi-clock-history fs-4"></i>

                                    <div>

                                        <strong>
                                            Ticket vencido
                                        </strong>

                                        <div>

                                            Este ticket superó su período
                                            de vigencia de 3 meses.

                                        </div>

                                    </div>

                                </div>

                            </div>
                        @endif



                        <hr class="my-4">


                        {{-- INFORMACIÓN --}}
                        <h5 class="fw-bold mb-4">

                            <i class="bi bi-info-circle me-2 text-success"></i>

                            Información del ticket

                        </h5>


                        <div class="row g-4">


                            {{-- NOMBRE --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Visitante
                                </div>

                                <div class="fw-semibold">
                                    {{ $venta->nombre_cliente }}
                                </div>

                            </div>


                            {{-- RUT --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    RUT
                                </div>

                                <div class="fw-semibold">
                                    {{ $venta->rut_cliente }}
                                </div>

                            </div>


                            {{-- PERSONAS --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Acompañantes
                                </div>

                                <div class="fw-semibold">
                                    {{ $venta->cantidad_personas }}
                                </div>

                            </div>


                            {{-- TOTAL --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Total pagado
                                </div>

                                <div class="fw-semibold">

                                    ${{ number_format($venta->total, 0, ',', '.') }}

                                </div>

                            </div>


                            {{-- EMISIÓN --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Fecha de emisión
                                </div>

                                <div class="fw-semibold">

                                    @if ($venta->pagada_at)
                                        {{ $venta->pagada_at->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif

                                </div>

                            </div>


                            {{-- VENCIMIENTO --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Válido hasta
                                </div>

                                <div class="fw-semibold">

                                    @if ($vencimiento)
                                        {{ $vencimiento->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif

                                </div>

                            </div>


                            {{-- ORDEN COMPRA --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Orden de compra
                                </div>

                                <div class="fw-semibold">
                                    {{ $venta->webpay_buy_order }}
                                </div>

                            </div>


                            {{-- ESTADO --}}
                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Estado
                                </div>

                                <div class="fw-semibold">
                                    {{ $estadoTicket }}
                                </div>

                            </div>


                        </div>


                        {{-- AVISO FINAL --}}
                        @if ($estadoTicket === 'VIGENTE')
                            <div class="alert alert-success mt-4 mb-0">

                                <i class="bi bi-shield-check me-2"></i>

                                Este ticket está disponible para ser utilizado
                                en el acceso vehicular al parque.

                            </div>
                        @endif


                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
