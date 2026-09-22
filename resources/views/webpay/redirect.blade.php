@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body text-center p-5">

                        <div class="spinner-border text-success mb-4" role="status"></div>

                        <h3>
                            Redirigiendo a Webpay
                        </h3>

                        <p class="text-muted">
                            Estamos conectando con Transbank...
                        </p>


                        <form id="webpayForm" method="POST" action="{{ $url }}">

                            <input type="hidden" name="token_ws" value="{{ $token }}">

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                document
                    .getElementById('webpayForm')
                    .submit();

            }
        );
    </script>
@endsection