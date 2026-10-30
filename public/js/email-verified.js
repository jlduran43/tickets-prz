
document.addEventListener('DOMContentLoaded', function () 
{

    const contador = document.getElementById('contador');

    if (!contador) return;

    const destino = contador.dataset.redirect;

    if (!destino) 
    {
        console.error('No se encontró la URL de redirección.');
        return;
    }

    let segundos = 3;

    const intervalo = setInterval(function () 
    {
        segundos--;

        contador.textContent = Math.max(segundos, 0);

        if (segundos <= 0) 
        {

            clearInterval(intervalo);
            window.location.assign(destino);
        }

    }, 1000);
});
