<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\category;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'title' => (string)$category->name,
            'details' => (string)$category->email,
            'createDate' => (string)$category->created_at,
            'updateDate' => (string)$category->updated_at,
            'deleteDate' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $category->id),
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', $category->id),
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $category->id),
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $category->id),
                ],
            ], 
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
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
            'name' => 'title',
            'description' => 'details',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }
}
