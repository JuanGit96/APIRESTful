<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'quantity' => (string)$transaction->quantity,
            'buyerIdentifier' => (int)$transaction->buyer_id,
            'productIdentifier' => (int)$transaction->product_id,
            'createDate' => (string)$transaction->created_at,
            'updateDate' => (string)$transaction->updated_at,
            'deleteDate' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null, 
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id),
                ],
                [
                    'rel' => 'transactions.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $transaction->product_id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $atributes = [
            'identifier' => 'id',
            'quantity' => 'quantity',
            'buyerIdentifier' => 'buyer_id',
            'productIdentifier' => 'product_id',
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
            'quantity' => 'quantity',
            'buyer_id' => 'buyerIdentifier',
            'product_id' => 'productIdentifier',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }
}
