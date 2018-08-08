@component('mail::message')
# Hola {{$user->name}}

Cambiaste tu correo electronico
Porfavor verifica la cuenta usando el siguiente enlace:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Confirmar mi cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent