<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{
    
    public $key;
    
    public function __construct(){
        $this->key = 'clave_super_secreta-7429542895';
    }
    
    public function signup($email, $password, $getToken = null){
        //BUSCAR SI EXISTE EL USUARIO CON SUS CREDENCIALES
            $user = User::where([
                'email' => $email,
                'password' => $password
            ])->first();
        //COMPROBAR SI SON CORRECTAS (OBJETO)
            $signup = false;
            if(is_object($user)){
                $signup = true;
            }
        //GENERAR TOKEN CON LOS DATOS DEL USUSARIO IDENTIFICADO
            if($signup){
                $token = array(
                  'sub' => $user->id,
                  'email' => $user->email,
                  'name' => $user->name,
                  'surname' => $user->surname,
                  'description' => $user->description,
                  'image' => $user->image,
                  'iat' => time(),
                  'exp' => time() + (7 * 24 * 60 * 60)
                );
                $jwt = JWT::encode($token, $this->key, 'HS256');
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);
                
                
        //DEVOLVER LOS DATOS DECODIFICADOS O EL TOKEN, EN FUNCION DE UN PARAMETRO
                if(is_null($getToken)){
                    $data = $jwt;
                }else{
                    $data = $decoded;
                }
            }else{
                $data = array(
                    'status' => 'error',
                    'message' => 'login incorrecto'
                );
            }
        return $data;
    }
    
    public function checkToken($jwt, $getIdentity = false){
        $auth = false;
        
        try{
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }
        
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            
            $auth = true;
            
        }else{
            $auth = false;
        }
        
        if($getIdentity){
            return $decoded;
        }
        
        return $auth;
    }
    
}