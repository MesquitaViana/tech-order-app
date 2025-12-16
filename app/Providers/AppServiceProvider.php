<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        /**
         * 🔐 Rate limit - Login Admin
         * 5 tentativas por minuto por IP
         */
        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        /**
         * 👤 Rate limit - Login Cliente
         * 5 tentativas por minuto por IP + email
         */
        RateLimiter::for('customer-login', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->ip() . '|' . ($request->input('email') ?? 'guest')
            );
        });

        /**
         * 🔔 Rate limit - Webhook Luna
         * 60 requisições por minuto por IP (bem permissivo)
         */
        RateLimiter::for('luna-webhook', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
