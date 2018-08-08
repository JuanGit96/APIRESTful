<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use App\Product;
use App\User;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //actualizar estado de los productos usando eventos del modelo
        Product::updated(function($product){
            if($product->quantity == 0 && $product->estaDisponible()){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });

        //evento para envio de correos
        User::created(function($user){
            // Mail::to($user->email);
            //Mail::to($user)->send(new UserCreated($user));
            retry(5, function() use ($user){//repite si no funciona 5 veces 100 milisegundos
                Mail::to($user)->send(new UserCreated($user));
            },100);
        });

        User::updated(function($user){
            if($user->isDirty('email')){
                retry(5, function() use ($user){
                    Mail::to($user)->send(new UserMailChanged($user));
                },100);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
