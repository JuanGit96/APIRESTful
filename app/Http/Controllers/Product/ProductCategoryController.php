<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        //Realizando actualizacion de la relacion Product-Category
        //no es una operacion de creacion
        //agregamos categoria existente a un producto

        /**
         * Metodos a usar
         * sync ->reemplaza lista anterior por lista actual
         * $product->categories()->sync([$category->id]);
         * attach -> agrega una nueva categoria (pero la agrega caada vez que 
         * ejecutamos url del servicio... agrega y agrega la misma categoria asi exista)
         * syncWithoutDetaching->agrega sin eliminar anteriores
         * agrega con exito y si ya existe el efecto es nulo
         */

        $product->categories()->syncWithoutDetaching([$category->id]);        

        return $this->showAll($product->categories);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        //eliminar category de product

        if(!$product->categories()->find($category->id)){//si no encuentra la caategoria
            //no existe categoria
            return $this->errorResponse('la categoria especificada no es una categoria relacionada a este producto',404);   
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);

    }
}
