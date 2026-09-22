@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body text-center p-5">

                        <i class="bi bi-x-circle text-danger" style="font-size: 64px;"></i>

                        <h2 class="mt-3">
                            No pudimos completar el pago
                        </h2>

                        @if (session('error'))
                            <div class="alert alert-danger mt-4">

                                {{ session('error') }}

                            </div>
                        @endif


                        <a href="{{ route('ventas.create') }}" class="btn btn-success mt-3">
                            Intentar nuevamente
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection