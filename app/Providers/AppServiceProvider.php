<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, string $url) {

            return (new MailMessage)
                ->subject('Verifica tu correo electrónico - Tickets PRZ')
                ->greeting('¡Hola ' . $notifiable->name . '!')
                ->line('Gracias por registrarte en Tickets PRZ.')
                ->line('Para activar tu cuenta y comenzar a comprar tus tickets, necesitamos verificar tu correo electrónico.')
                ->action('Verificar correo electrónico', $url)
                ->line('Este enlace de verificación tiene una duración limitada por seguridad.')
                ->line('Si tú no creaste esta cuenta, puedes ignorar este mensaje.')
                ->salutation('Saludos, Parque Museo Pedro del Río Zañartu');
        });
    }
}
