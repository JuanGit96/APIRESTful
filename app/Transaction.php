<?php

namespace App;
use App\Product;
use App\Buyer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;

class Transaction extends Model
{
    use SoftDeletes;

    public $transformer = TransactionTransformer::class;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'quantity', 'buyer_id','product_id'
    ];

    /*Una transaccion tiene un producto */
    public function product()
    {
        return $this->belongsTo(Product::class); 
    }    

    /*Una transaccion tiene un comprador */
    public function buyer()
    {
        return $this->belongsTo(Buyer::class); 
    }      
}
