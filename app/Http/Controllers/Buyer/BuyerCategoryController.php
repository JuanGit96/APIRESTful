<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        Parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')->get()
                            ->pluck('product.categories')
                            //hasta acá obtenemos colleccion de categorias
                            //(array por cada producto)
                            ->collapse()//juntar todas las listas en una sola
                            ->unique('id')//unique para evitar que se repitan (gracias al id)
                            ->values();//reorganiza indices y elimina los vacios
                
        return $this->showAll($categories);
    }

}
