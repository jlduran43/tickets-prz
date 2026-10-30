document.addEventListener(
    'DOMContentLoaded',
    function () 
    {
        let segundos = 3;

        const contador = document.getElementById('contador');

        const intervalo = setInterval(
            function () 
            {
                segundos--;

                if (contador) 
                {
                    contador.textContent = segundos;
                }

                if (segundos <= 0) 
                {
                    clearInterval(intervalo);

                    window.location.href = "{{ route('ventas.create') }}";
                }
            },
            1000
        );
    }
);