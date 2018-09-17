<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
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
        $sellers = $buyer->transactions()->with('product.seller')->get()
                        ->pluck('product.seller')//seller está al interior de product
                        ->unique('id')//unique para evitar que se repitan (gracias al id)
                        ->values();//reorganiza indices y elimina los vacios                

        return $this->showAll($sellers);

    }

}
