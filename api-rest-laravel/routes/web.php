<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

//RUTAS PRUEBA
Route::get('/pruebas/{nombre?}', function($nombre = null){
    $texto= '<h2>Texto de ruta</h2>';
    $texto .= 'Nombre: '.$nombre;
    
    return view('pruebas',array(
        'texto' => $texto
            ));
    
});

Route::get('/animales', 'PruebasController@index');

Route::get('/test-orm', 'PruebasController@testOrm');


//RUSTAS API

        /*METODOS HTTP comunes
                *GET: CONSEGUIR DATOS O RECURSOS
                *POST: GUARDAR DATOS O RECURSOS O HACER LOGICA DESDE UN FORMULARIO
                *PUT: ACTUALIZAR DATOS O RECURSOS
                *DELETE: ELIMINAR DATOS O RECURSOS 
        */
        //RUTAS DE PRUEBA
        //Route::get('/usuario/pruebas', 'UserController@pruebas');
        //Route::get('/categoria/pruebas', 'CategoryController@pruebas');
        //Route::get('/post/pruebas', 'PostController@pruebas');
        
        //RUTAS DE CONTROLADOR DE USUARIO
        Route::post('/api/register', 'UserController@register');
        Route::post('/api/login', 'UserController@login');
        Route::put('/api/user/update', 'UserController@update');
        Route::post('/api/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
        Route::get('/api/user/avatar/{filename}', 'UserController@getImage');
        Route::get('/api/user/detail/{id}', 'UserController@detail');

        
        //RUTAS DE CONTROLADOR DE CATEGORIAS
        Route::resource('/api/category', 'CategoryController');
        
        //RUTAS DE CONTROLADOR DE ENTRADAS
        Route::resource('/api/post', 'PostController');
        Route::post('/api/post/upload', 'PostController@upload');
        Route::get('/api/post/image/{filename}', 'PostController@getImage');
        Route::get('/api/post/category/{id}', 'PostController@getPostByCategory');
        Route::get('/api/post/user/{id}', 'PostController@getPostByUser');

        