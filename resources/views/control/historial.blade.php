@extends('layouts.app')

@section('title', 'Historial de validaciones')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="fw-bold mb-1">
                            Historial de validaciones
                        </h2>

                        <p class="text-muted mb-0">
                            Últimos tickets utilizados.
                        </p>

                    </div>

                    <a href="{{ route('control.scanner') }}" class="btn btn-success">

                        <i class="bi bi-qr-code-scan me-2"></i>

                        Escanear ticket

                    </a>

                </div>


                <div class="card shadow-sm border-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="px-4">
                                        Folio
                                    </th>

                                    <th>
                                        Visitante
                                    </th>

                                    <th>
                                        Personas
                                    </th>

                                    <th>
                                        Validado
                                    </th>
                                    <th>
                                        Validado por
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($ventas as $venta)
                                    <tr>

                                        <td class="px-4 fw-semibold">
                                            {{ $venta->folio }}
                                        </td>

                                        <td>
                                            {{ $venta->nombre_cliente }}
                                        </td>

                                        <td>
                                            {{ $venta->cantidad_personas }}
                                        </td>

                                        <td>
                                            {{ $venta->validada_at?->format('d/m/Y H:i') }}
                                        </td>
                                        <td>

                                            {{ $venta->usuarioValidador?->name ?? '-' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center py-5 text-muted">

                                            Todavía no hay tickets validados.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
