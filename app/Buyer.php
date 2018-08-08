<?php

namespace App;
use App\Transaction;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

class Buyer extends User
{
    public $transformer = BuyerTransformer::class;

    //construir e inicializar modelo
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BuyerScope);   
    }


    /*Un comprador tiene muchas transacciones */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
