<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'name' => (string)$product->name,
            'description' => (string)$product->description,
            'quantity' => (string)$product->quantity,
            'disponible' => ($product->status == 'disponible'),
            'image' => url("img/{$product->image}"),
            'sellerIdentifier' => (int)$product->seller_id,
            'createDate' => (string)$product->created_at,
            'updateDate' => (string)$product->updated_at,
            'deleteDate' => isset($product->deleted_at) ? (string) $product->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'products.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel' => 'products.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id),
                ],
                [
                    'rel' => 'products.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
            ],  
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes = [
            'identifier' => 'id',
            'name' => 'name',
            'description' => 'description',
            'quantity' => 'quantity',
            'disponible' => 'status',
            'image' => 'image',
            'sellerIdentifier' => 'seller_id',
            'createDate' => 'created_at',
            'updateDate' => 'updated_at',
            'deleteDate' => 'deleted_at', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }

    public static function transformedAttribute($index)
    {
        $atributes = [
            'id' => 'identifier',
            'name' => 'name',
            'description' => 'description',
            'quantity' => 'quantity',
            'status' => 'disponible',
            'image' => 'image',
            'seller_id' => 'sellerIdentifier',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }
}
