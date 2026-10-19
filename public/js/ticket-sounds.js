// ====================================
// SONIDOS DEL ESCÁNER TICKETS PRZ
// ====================================

const sonidoTicketValido = new Audio(
    '/sounds/ticket-valido.wav'
);

sonidoTicketValido.preload = 'auto';
sonidoTicketValido.volume = 0.8;

// Habilitar audio tras interacción del usuario
async function habilitarSonidoTicket() {
    try {
        sonidoTicketValido.muted = true;

        await sonidoTicketValido.play();

        sonidoTicketValido.pause();
        sonidoTicketValido.currentTime = 0;
        sonidoTicketValido.muted = false;

    } catch (error) {
        sonidoTicketValido.muted = false;
        console.warn(
            'No se pudo habilitar el sonido:',
            error
        );
    }
}

// Reproducir ticket válido
function reproducirSonidoTicketValido() {
    try {
        sonidoTicketValido.pause();
        sonidoTicketValido.currentTime = 0;

        sonidoTicketValido.play().catch(error => {
            console.warn(
                'No se pudo reproducir el sonido:',
                error
            );
        });

    } catch (error) {
        console.warn('Error de audio:', error);
    }
}

const sonidoTicketUtilizado = new Audio(
    '/sounds/ticket-utilizado.wav'
);

sonidoTicketUtilizado.preload = 'auto';
sonidoTicketUtilizado.volume = 0.8;

function reproducirSonidoTicketUtilizado() {
    try {
        sonidoTicketUtilizado.pause();
        sonidoTicketUtilizado.currentTime = 0;

        sonidoTicketUtilizado.play().catch(error => {
            console.warn(
                'No se pudo reproducir sonido utilizado:',
                error
            );
        });
    } catch (error) {
        console.warn(error);
    }
}