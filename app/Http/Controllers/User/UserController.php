<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;

class UserController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. UserTransformer::class)
        ->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' =>'required|min:6|confirmed'
        ];

        $this->validate($request,$reglas);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);

        return $this->showOne($usuario,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    public function show(User $user)
    {
        /*NO se usa gracias a inyeccion implicita
        $usuario = User::findOrFail($id);*/

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $reglas = [
            'email' => 'email|unique:users,email,'. $user->id,
            'password' =>'min:6|confirmed',
            'admin' => 'in:'.User::USUARIO_REGULAR.','.User::USUARIO_ADMINISTRADOR,
        ];

        $this->validate($request,$reglas);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){           
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;        
        }

        if($request->has('password') && $user->email != $request->email){           
            $user->password = bcrypt($request->password);        
        }

        if($request->has('admin')){           
            if(!$user->esVerificado()){
                return $this->errorResponse('Unicamente los usuarios verificados pueden ser administrador',409);
            }

            $user->admin = $request->admin;    
        }

        if(!$user->isDirty()){
            return $this->errorResponse('se debe especificar al menos un valor diferente para actualizar',422);
        }   

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    public function verify($token)
    {
        //el token de verificacion debe ser igual a valor recibido
        $user=User::where('verification_token',$token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;

        //remover token de verificacion actual
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('la cuenta ha sido verificada');
    }

    public function resend(User $user)
    {
        if($user->esVerificado()){
            return $this->errorResponse('el usuario ha sido verificado',409);
        }

        retry(5, function() use ($user){//repite si no funciona 5 veces 100 milisegundos
            Mail::to($user)->send(new UserCreated($user));
        },100);

        return $this->showMessage('El correo de verificacion se ha reenviado');
    }
}
