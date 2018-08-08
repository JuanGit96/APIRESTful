<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //acceso directamente a la funcion transaction y no al modelo
        $products = $buyer->transactions() 
        //eloquent incluye una lista de productos
        //acceder a las relaciones
        ->with('product')->get()
        //pluk permite trabajar directamente sobre la coleccion
        //e indicar que solo se quiere una parte de la misma
        ->pluck('product');

        return $this->showAll($products);
    }

}
