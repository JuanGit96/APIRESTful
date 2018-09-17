<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Rutas para laravel Passport
         */
        Passport::routes();

        //expiracion del token
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));

        //tiempo de expiracion a los refreshToken
        //Luego de esa fecha tendría que hacer de nuevo un flujo de autorización
        Passport::refreshtokensExpireIn(Carbon::now()->addDays(30));

        /**
         * Para usar Grant Type implicito
         */
        Passport::enableImplicitGrant();
        
    }
}
