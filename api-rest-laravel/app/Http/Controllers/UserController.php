<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

class UserController extends Controller {

    public function pruebas(Request $request) {
        return "Accion de pruebas del USER_CONTROLLER";
    }

    public function register(Request $request) {

        //RECOGER LOS DATOS DEL USUARIO POST 
        $json = $request->input('json', null);
        $params = json_decode($json); //genera objeto
        $params_array = json_decode($json, true); //genera array

        if (!empty($params) && !empty($params_array)) {
            //LIMPIAR DATOS
            $params_array = array_map('trim', $params_array);


            //VALIDAR DATOS
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|alpha',
                        'email' => 'required|email|unique:users',
                        'password' => 'required'
            ]);

            if ($validate->fails()) {
                //Validacuion a fallado
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            } else {
                //Validacion pasada correctamente
                //CIFRAR CONTRASEÑA
                $pwd = hash('sha256', $params->password);

                //CREAR USUARIO
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';

                //GUARDAR USUARIO
                $user->save();


                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos'
            );
        }










        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        $jwtAuth = new \JwtAuth();

        //RECIBIR DATOS POR POST
        $json = $request->input('json', null);
        $params = json_decode($json); //genera objeto
        $params_array = json_decode($json, true); //genera array
        //VALIDAR DATOS
        if (!empty($params) && !empty($params_array)) {
            $validate = \Validator::make($params_array, [
                        'email' => 'required|email',
                        'password' => 'required'
            ]);

            if ($validate->fails()) {
                //Validacuion a fallado
                $signup = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha podido identificar',
                    'errors' => $validate->errors()
                );
            } else {
                //CIFRAR CONTRASEÑA
                $pwd = hash('sha256', $params->password);
                //DEVOLVER TOKEN O DATOS
                $signup = $jwtAuth->signup($params->email, $pwd);
                if (!empty($params->gettoken)) {
                    $signup = $jwtAuth->signup($params->email, $pwd, true);
                }
            }
        } else {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos'
            );
        }
        return response()->json($signup, 200);
    }

    public function update(Request $request) {
        //COMPROBAR SI EL USUARIO ESTA IDENTIFICADO
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        //RECOGER LOS DATOS POR POST
            $json = $request->input('json', null);
            $params_array = json_decode($json, true);
            

        if ($checkToken && !empty($params_array)) {
         //ACTUALIZAR USUARIO
            
            //SACAR USUARIO VERIFICADO
            $user = $jwtAuth->checkToken($token, true);
            
            
            //VALIDAR DATOS
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|alpha',
                        'email' => 'required|email|unique:users,'.$user->sub
                ]);
            //QUITAR LOS CAMPOS QUE NO QUIERO ACTUALIZAR
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);
                 
            //ACTUALIZAR USUARIO EN BBDD
            $user_update = User::where('id', $user->sub)->update($params_array);
            
            //DEVOLVER ARRAY CON RESULTADO
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'change' => $params_array
            );
            
        }else{
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado'
            );
        }
        return response()->json($data, $data['code']);
    }
    
    public function upload(Request $request){
        //RECOGER DATOS DE LA PETICION
        $image = $request->file('file0');
        //VALIDACION DE IMAGENES
        $validate = \Validator::make($request->all(), [
                        'file0' => 'required|image|mimes:jpg,jpeg,png'
            ]);
        
        
        //SUBIR Y GUARDAR IMAGEN
        if(!$image || $validate->fails()){
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen'
            );
            
        }else{
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));
            
            $data = array(
                'image' => $image_name,
                'code' => 200,
                'status' => 'success',
            );
        }
        
        return response()->json($data, $data['code']);
    }
    
    public function getImage($filename){
        $isset = \Storage::disk('users')->exists($filename);
        if($isset){
            $file = \Storage::disk('users')->get($filename);
            return new Response($file, 200);
        }else{
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
            return response()->json($data, $data['code']);
        }
    }
    
    public function detail($id){
        $user = User::find($id);
        if(is_object($user)){
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        }else{
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe'
            );
        }
        return response()->json($data, $data['code']);
    }
}
