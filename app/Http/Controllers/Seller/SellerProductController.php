<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\User;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\ HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Storage;
use App\Transformers\ProductTransformer;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. ProductTransformer::class)
        ->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        //crear instancias de producto a vendedor/usuario especifico
        $reglas = [
            'name' => 'required', 
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $this->validate($request,$reglas);

        $data = $request->all();
        $data['status'] = product::PRODUCTO_NO_DISPONIBLE;

        /**
         * la imagen vendra directamente de la peticion y laravel sabrá que este es un arcivo
         * el metodo store('ubicacion','sistema de archivos')
         * se creará nombre aleatorio y unico a la imagen
         */
        $data['image'] = $request->image->store('');

        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product,201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        //validando integridad del vendedor
        $this->verificarVendedor($seller,$product);

        $rules = [
            'quantity' => 'integer|min:1',
            'image' => 'image',
            'status' => 'in:' . Product::PRODUCTO_DISPONIBLE .',' . Product::PRODUCTO_NO_DISPONIBLE
        ];

        $this->validate($request,$rules);

        //lenar primeras instancias de la actualizacion
        $product->fill(array_filter($request->only('name','description','quantity')));

        if($request->has('status')){
            $product->status = $request->status;

            if($product->estaDisponible() && $product->categories()->count() == 0){
                return $this->errorResponse('Producto activo debe tener al menos una categoria',409);
            }
        }

        if($request->hasFile('image')){
            //eliminar imagen junto con el producto
            //Storage permite interactuar con el sistem de archivos
            Storage::delete($product->image);   
            $product->image = $request->image->store('');
        }

        if(!$product->isDirty()){
            return $this->errorResponse('se debe especificar al menos un valor diferente para actualizar',422);
        } 
        
        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        //verificar que el vendedor es el propietario del producto
        $this->verificarVendedor($seller,$product);
        $product->delete();
        
        //eliminar imagen junto con el producto
        //Storage permite interactuar con el sistem de archivos
        Storage::delete($product->image);

        return $this->showOne($product);
    }


    public function verificarVendedor(Seller $seller, Product $product)
    {
        //validando integridad del vendedor
        if($seller->id != $product->seller_id)
        {
            throw new HttpException(422,
            'Vendedor solicitante no es el vendedor del producto');
        }
    }
}
