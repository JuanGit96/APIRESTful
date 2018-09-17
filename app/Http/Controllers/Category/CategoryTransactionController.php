<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
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
    public function index(Category $category)
    {
        $transactions = $category->products()
                        //obtenemos productos que tengan asociada al menos una transaccion
                        ->whereHas('transactions') 
                        ->with('transactions')->get()
                        ->pluck('transactions')
                        ->collapse() //une todos los arreglos
                        ->unique()
                        ->values(); //ordena y borra espacions vacios

        return $this->showAll($transactions);
    }
}
