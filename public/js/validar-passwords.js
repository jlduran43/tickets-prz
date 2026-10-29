document.addEventListener('DOMContentLoaded', function () 
{
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');
    const feedback = document.getElementById('passwordFeedback');

    function validarPasswords() 
    {
        if (!confirmation.value) 
        {
            feedback.innerHTML = '';
            confirmation.classList.remove('is-valid', 'is-invalid');

            return;
        }

        if (password.value === confirmation.value) 
        {
            confirmation.classList.remove('is-invalid');
            confirmation.classList.add('is-valid');

            feedback.innerHTML =    '<span class="text-success">' +
                                    '<i class="bi bi-check-circle me-1"></i>' +
                                    'Las contraseñas coinciden.' +
                                    '</span>';

        } else 
        {
            confirmation.classList.remove('is-valid');
            confirmation.classList.add('is-invalid');

            feedback.innerHTML =    '<span class="text-danger">' +
                                    '<i class="bi bi-x-circle me-1"></i>' +
                                    'Las contraseñas no coinciden.' +
                                    '</span>';
        }
    }

    password.addEventListener('input', validarPasswords);
    confirmation.addEventListener('input', validarPasswords);

});