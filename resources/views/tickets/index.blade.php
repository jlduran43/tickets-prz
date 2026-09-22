@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-10">


                {{-- CABECERA --}}
                <div
                    class="d-flex flex-column flex-md-row
                        justify-content-between
                        align-items-md-center
                        gap-3 mb-4">

                    <div>

                        <h2 class="fw-bold mb-1">
                            Mis tickets
                        </h2>

                        <p class="text-muted mb-0">
                            Consulta tus tickets vehiculares comprados.
                        </p>

                    </div>


                    <div>

                        <a href="{{ route('ventas.create') }}" class="btn btn-success">

                            <i class="bi bi-plus-circle me-2"></i>

                            Comprar nuevo ticket

                        </a>

                    </div>

                </div>



                @if ($ventas->isEmpty())
                    {{-- SIN TICKETS --}}
                    <div class="card shadow-sm border-0">

                        <div class="card-body text-center py-5">

                            <i class="bi bi-ticket-perforated text-muted" style="font-size: 60px;">
                            </i>

                            <h4 class="mt-3">
                                Aún no tienes tickets
                            </h4>

                            <p class="text-muted">
                                Los tickets que compres aparecerán aquí.
                            </p>

                            <a href="{{ route('ventas.create') }}" class="btn btn-success mt-2">

                                Comprar ticket

                            </a>

                        </div>

                    </div>
                @else
                    <div class="row g-4">

                        @foreach ($ventas as $venta)
                            @php

                                $vencimiento = $venta->pagada_at ? $venta->pagada_at->copy()->addMonths(3) : null;

                                if ($venta->validada_at) {
                                    $estadoTicket = 'UTILIZADO';
                                    $badge = 'secondary';
                                    $icono = 'bi-check2-circle';
                                } elseif ($vencimiento && now()->greaterThan($vencimiento)) {
                                    $estadoTicket = 'VENCIDO';
                                    $badge = 'warning';
                                    $icono = 'bi-clock-history';
                                } else {
                                    $estadoTicket = 'VIGENTE';
                                    $badge = 'success';
                                    $icono = 'bi-ticket-perforated';
                                }

                            @endphp


                            <div class="col-12 col-md-6">

                                <div class="card h-100 shadow-sm border-0">

                                    <div class="card-body p-4">


                                        {{-- ESTADO --}}
                                        <div
                                            class="d-flex
                                                justify-content-between
                                                align-items-start
                                                mb-3">

                                            <div>

                                                <div class="text-muted small">
                                                    Ticket
                                                </div>

                                                <h5 class="fw-bold mb-0">
                                                    {{ $venta->folio }}
                                                </h5>

                                            </div>


                                            <span
                                                class="badge
                                                     bg-{{ $badge }}
                                                     rounded-pill
                                                     px-3 py-2">

                                                <i class="bi {{ $icono }} me-1"></i>

                                                {{ $estadoTicket }}

                                            </span>

                                        </div>



                                        <hr>



                                        {{-- INFORMACIÓN --}}
                                        <div class="row g-3">

                                            <div class="col-6">

                                                <div class="text-muted small">
                                                    Fecha de compra
                                                </div>

                                                <div class="fw-semibold">

                                                    @if ($venta->pagada_at)
                                                        {{ $venta->pagada_at->format('d/m/Y') }}
                                                    @else
                                                        -
                                                    @endif

                                                </div>

                                            </div>


                                            <div class="col-6">

                                                <div class="text-muted small">
                                                    Total
                                                </div>

                                                <div class="fw-semibold">

                                                    ${{ number_format($venta->total, 0, ',', '.') }}

                                                </div>

                                            </div>


                                            <div class="col-6">

                                                <div class="text-muted small">
                                                    Acompañantes
                                                </div>

                                                <div class="fw-semibold">
                                                    {{ $venta->cantidad_personas }}
                                                </div>

                                            </div>


                                            <div class="col-6">

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

                                        </div>



                                        {{-- UTILIZADO --}}
                                        @if ($venta->validada_at)
                                            <div
                                                class="alert alert-secondary
                                                    mt-3 mb-0 py-2">

                                                <small>

                                                    Utilizado el

                                                    <strong>
                                                        {{ $venta->validada_at->format('d/m/Y H:i') }}
                                                    </strong>

                                                </small>

                                            </div>
                                        @endif



                                        {{-- BOTÓN --}}
                                        <div class="d-flex gap-2 mt-3">

                                            {{-- Ver ticket --}}
                                            <a href="{{ route('tickets.show', $venta) }}"
                                                class="btn btn-outline-success flex-fill">
                                                <i class="bi bi-eye me-2"></i>
                                                Ver ticket
                                            </a>

                                            {{-- Descargar PDF --}}
                                            <a href="{{ route('ticket.pdf.descargar', $venta->token_ticket) }}"
                                                class="btn btn-success flex-fill">
                                                <i class="bi bi-file-earmark-pdf me-2"></i>
                                                Descargar PDF
                                            </a>

                                        </div>


                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>
                @endif


            </div>

        </div>

    </div>

@endsection
