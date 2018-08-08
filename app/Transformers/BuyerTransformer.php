<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Buyer;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int)$buyer->id,
            'name' => (string)$buyer->name,
            'e-mail' => (string)$buyer->email,
            'isVerified' => ($buyer->verified === '1'),
            'createDate' => (string)$buyer->created_at,
            'updateDate' => (string)$buyer->updated_at,
            'deleteDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null, 
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('buyers.show', $buyer->id),
                ],
                [
                    'rel' => 'buyer.transactions',
                    'href' => route('buyers.transactions.index', $buyer->id),
                ],
                [
                    'rel' => 'buyers.categories',
                    'href' => route('buyers.categories.index', $buyer->id),
                ],
                [
                    'rel' => 'buyers.sellers',
                    'href' => route('buyers.sellers.index', $buyer->id),
                ],
                [
                    'rel' => 'buyers.products',
                    'href' => route('buyers.products.index', $buyer->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $buyer->id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes = [
            'identifier' => 'id',
            'name' => 'name',
            'e-mail' => 'email',
            'isVerified' => 'verified',
            'createDate' => 'created_at',
            'updateDate' => 'updated_at',
            'deleteDate' => 'deleted_at', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }

    public static function transformedAttribute($index)
    {
        $atributes = [
            'id'=> 'identifier',
            'name'=> 'name',
            'email'=> 'e-mail',
            'verified'=> 'isVerified',
            'created_at'=> 'createDate',
            'updated_at'=> 'updateDate',
            'deleted_at'=> 'deleteDate', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }
}
