@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow-sm">

                    <div class="card-header bg-white">

                        <h4 class="mb-0">
                            Nuevo tipo de entrada
                        </h4>

                    </div>

                    <div class="card-body">

                        <form
                            action="{{ route('admin.tipos-entradas.store') }}"
                            method="POST">

                            @csrf

                            @include('admin.tipos-entradas._form')

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection