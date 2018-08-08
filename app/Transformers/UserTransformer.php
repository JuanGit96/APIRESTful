<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'name' => (string)$user->name,
            'e-mail' => (string)$user->email,
            'isVerified' => ($user->verified === 'true'),
            'isAdmin' => ($user->admin === 'true'),
            'createDate' => (string)$user->created_at,
            'updateDate' => (string)$user->updated_at,
            'deleteDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null, 
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id),
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
            'pass' => 'password',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
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
            'email' => 'e-mail',
            'password' => 'pass',
            'verified' => 'isVerified',
            'admin' => 'isAdmin',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate', 
        ];

        return isset($atributes[$index]) ? $atributes[$index] : null; 
    }
}
