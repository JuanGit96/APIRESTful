<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. TransactionTransformer::class)
        ->only(['store']);
    }

    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request,$rules);

        //validar que el comprador y el vendedor sean diferentes
        if($product->seller_id == $buyer->id){
            return $this->errorResponse('El comprador no puede ser el mismo vendedor',409);
        }

        //verificar que el comprador sea verificado
        if(!$buyer->esVerificado()){
            return $this->errorResponse('El comprador no ha sido verificado',409);
        }

        //verificar que el vendedor sea verificado
        if(!$product->seller->esVerificado()){
            return $this->errorResponse('El vendedor no ha sido verificado',409);
        }

        //verificar que el producto sea disponible
        if(!$product->estaDisponible()){
            return $this->errorResponse('El producto no está disponible',409);
        }  
        
        //verificar que la cantidaad de la transaccion deseada no supera la cantidad del producto
        if($product->quantity < $request->quantity){
            return $this->errorResponse('Cantidad de producto insuficiente, imposible crear transaccion',409);
        }

        //creacion de la transaccion

        //usando transacciones de la base de datos
        //si algo falla revierte todos los cambios
        return DB::transaction(function () use($request,$product,$buyer) {
            $product->quantity -= $request->quantity;
            $product->save();
            
            //creamos la transaccion
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);

            return $this->showOne($transaction,201);

        });
    }

}
