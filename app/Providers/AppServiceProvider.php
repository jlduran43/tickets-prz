<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

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
        VerifyEmail::toMailUsing(
            function (object $notifiable, string $url) {

                return (new MailMessage)
                    ->subject('Verifica tu correo electrónico - Tickets PRZ')
                    ->view('emails.verificar-correo', [
                        'usuario' => $notifiable,
                        'url' => $url,
                    ]);
            }
        );


        ResetPassword::toMailUsing(
            function (object $notifiable, string $url) {

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
        );
    }
}
