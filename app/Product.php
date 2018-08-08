<?php

namespace App;
use App\Category;
use App\Product;
use App\Seller;
use App\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\ProductTransformer;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes;
    
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible'; 

    public $transformer = ProductTransformer::class;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description','quantity','status','image','seller_id'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function estaDisponible()
    {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    /*Una categoria tiene relacion muchos a muchos con producto */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }      

    /*Un producto tiene un vendedor */
    public function seller()
    {
        return $this->belongsTo(Seller::class); 
    }

    /*Un producto posee muchas transacciones */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
