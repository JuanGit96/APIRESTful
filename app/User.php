<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\UserTransformer;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    const USUARIO_VERIFICADO = 'true';
    const USUARIO_NO_VERIFICADO = 'false';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    public $transformer = UserTransformer::class;

    protected $table = 'users'; //nombre de la tabla que evita error con sellers y buyers
                                //ya que no tienen tabla asociada al modelo
    protected $dates = ['deleted_at'];
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verified','verification_token','admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token'
    ];

    /**
     * Mutador->modificar antes de la insercion en la bd
     * Accesor->Modifica despues de obtener en la bd
     */

    public function setNameAttribute($valor)
    {
        /*Antes de montar en la db caracteres en minuscula */
        $this->attributes['name'] = strtolower($valor);
    }

    public function getNameAttribute($valor)
    {
        //despues de tomar de la db muestra el nombre con las primeras letras en mayuscula
        //return ucfirst($valor); //primera letra en mayuscula
        return ucwords($valor); //primera de cada palabra en mayuscula  
    }

    public function setEmailAttribute($valor)
    {
        /*Antes de montar en la db caracteres en minuscula */
        $this->attributes['email'] = strtolower($valor);  
    }

    public function esVerificado()
    {
       return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador()
    {
       return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function generarVerificationToken()
    {
        return str_random(40);
    }
}
