<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Generar correo de recuperación.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Restablecer contraseña - Tickets PRZ')
            ->view('emails.restablecer-password', [
                'usuario' => $notifiable,
                'url' => $url,
                'expiracion' => config(
                    'auth.passwords.' .
                    config('auth.defaults.passwords') .
                    '.expire',
                    60
                ),
            ]);
    }
}
