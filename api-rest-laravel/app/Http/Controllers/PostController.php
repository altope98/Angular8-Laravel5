<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use App\Helpers\JwtAuth;

class PostController extends Controller {

    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'getImage', 'getPostByCategory', 'getPostByUser']]);
    }

    public function index(Request $request) {
        $posts = Post::all();


        return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'posts' => $posts
                        ], 200);
    }

    public function show($id) {
        $post = Post::find($id)->load('user')
                                ->load('category');
                               
                               
        if (is_object($post)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'posts' => $post
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La entrada no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        //RECOGER DATOS POR POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            //CONSEGUIR USUARIO IDENTIFICADO
            $user = $this->getIdentity($request);
            //VCVALIDAR DATOS
            $validate = \Validator::make($params_array, [
                        'title' => 'required',
                        'content' => 'required',
                        'category_id' => 'required',
                        'image' => 'required'
            ]);



            if ($validate->fails()) {
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post, faltan datos'
                ];
            } else {
                //GUARDAR POST
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;
                $post->save();
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => $post
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Envia los datos correctamente'
            ];
        }
        //DEVOLVER RESULTADO
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {
        //RECOGER POR POST
        
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        
        
        $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se ha enviado correctamente'
            ];
        if (!empty($params_array)) {
            //VALIDACION
            $user = $this->getIdentity($request);
            $validate = \Validator::make($params_array, [
                        'title' => 'required',
                        'content' => 'required',
                        'category_id' => 'required'
                            //'image' => 'required'
            ]);
            if($validate->fails()){
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }
            
            //QUITAR LO NO ACTUALIZABLE
            unset($params_array['category']);
            unset($params_array['id']);
            //unset($params_array['user_id']);
            unset($params_array['created_at']);
            unset($params_array['user']);
            //ACTUALIZAR
            
            
            //BUSCAR REGISTRO A ACTUALIZAR
            $post = Post::where('id', $id)->where('user_id', $user->sub)->first();
            if(!empty($post) && is_object($post)){
                //ACTUALIZAR EL REGISTRO CONCRETO
                $post->update($params_array);
                
                
                $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post,
                'changes' => $params_array
                ];
            }
            
            
            
        }
        //DEVOLVER DATOS
        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request) {
        //CONSEGUIR USUARIO IDENTIFICADO
        $user = $this->getIdentity($request);
        
        $post = Post::where('id',$id)->where('user_id', $user->sub)->first();
        if (!empty($post)) {
            $post->delete();

            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El post no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }
    
    private function getIdentity($request){
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);
        
        return $user;
    }
    
    public function upload(Request $request){
        //RECOGER LA IMAGEN DE LA POETICION
        $image = $request->file('file0');
        //VALIDAR IMAGEN
        $validate = \Validator::make($request->all(),[
            'file0' => 'required|image|mimes:jpg,jpeg,png'
        ]);
        //GUARDAR IMAGEN
        if(!$image || $validate->fails()){
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error al subir la imagen'
            ];
        }else{
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_name, \File::get($image));
            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            ];
            
        }
        
        //DEVOLVER DATOS
        return response()->json($data, $data['code']);
    }
        
    public function getImage($filename){
        //COMPROBAR SI EXISTE EL FICHERO
        $isset = \Storage::disk('images')->exists($filename);
        if($isset){
            //CONSEGUIR LA IMAGEN
            $file = \Storage::disk('images')->get($filename);
            //DEVLOLVER LA IMAGEN
            return new Response($file,200);

        }else{
            //MOSTRAR ERROR
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            ];
        }
        return response()->json($data, $data['code']); 
    }
    
    public function getPostByCategory($id){
        $posts = Post::where('category_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }

    public function getPostByUser($id){
        $posts = Post::where('user_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ], 200);        
    }
}
