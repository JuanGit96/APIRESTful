<?php

namespace App;
use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class Seller extends User
{
    public $transformer = SellerTransformer::class;

    //construir e inicializar modelo
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScope);   
    }

    /*Un vendedor posee muchas productos */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
