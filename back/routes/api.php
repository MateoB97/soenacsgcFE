<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/refresh-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return "Cache is refreshed";
});

Route::get('/backupsql', function() {
    exec("SqlCmd -E -S .\local -Q 'BACKUP DATABASE [sgc] TO DISK=’D:BackupsMyDB2.bak’'");
    return "backup done";
});

Route::get('/migrate-refresh-seed', function() {
    Artisan::call('migrate:refresh --seed');
Artisan::call('db:seed --class=SoenacSeeder');
    return "Migration done";
});

Route::get('/migrate', function() {
    Artisan::call('migrate');
    return "Migration done";
});

Route::get('/seedsoenac', function() {
    Artisan::call('db:seed --class=SoenacSeeder');
    return "seed done";
});

Route::get('/seedpermisos', function() {
    Artisan::call('db:seed --class=UserPermisosSeeder');
    return "seed done";
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function(){
    	Route::post('register', 'AuthController@register');
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['prefix' => 'users'], function(){
    // Users
    Route::get('', 'UserController@index');
    Route::get('{id}', 'UserController@show');
    Route::post('{id}', 'UserController@update');
    Route::get('estado/{id}/{cambio}', 'UserController@estado')->middleware('isAdmin');
    Route::get('reinitpassword/{id}', 'UserController@reiniciarPassword')->middleware('isAdmin');
    Route::post('/editar/cambiarpass', 'UserController@cambiarPass')->middleware('isAdminOrSelf');
    Route::apiResource('/permisos/categorias-permisos', 'UserPermisosCategoriasController');
    Route::apiResource('permisos/items', 'UserPermisosController')->middleware('permisos:10');
    Route::apiResource('permisos/roles', 'UserRolesController');
    Route::get('permisos/permisos-rol', 'UserPermisosController@permisosPorRol');
    Route::get('permisos/permisos-agrupados-categorias', 'UserPermisosController@permisosAgrupadosCategorias');
});
// rutas de gestion enterprises
Route::group(['prefix' => 'enterprises'], function(){
    // peticiones admin
    Route::get('/admin/adminEnterprises', 'enterpriseController@adminIndex');
    Route::get('/admin/showAdmin/{id}', 'enterpriseController@showAdmin');
    Route::get('/admin/confirmEnterpriseDian/{id}', 'enterpriseController@confirmEnterpriseDian');
    Route::post('/admin/downloadTxt', 'enterpriseController@downloadTxt');

    Route::get('', 'enterpriseController@index');
    Route::get('{id}', 'enterpriseController@show');
    Route::post('', 'enterpriseController@store');
    Route::put('{id}', 'enterpriseController@update');
    Route::delete('{id}', 'enterpriseController@Destroy');
    // peticiones soenac
    Route::get('/soenac/soenacCampos', 'enterpriseController@soenacCampos');
    Route::get('/soenac/softInfo/{id}', 'enterpriseController@softInfo');
    Route::get('/soenac/productionNumbers/{id}', 'enterpriseController@productionNumbers');
    Route::get('/soenac/verEmpresa/{id}', 'enterpriseController@verEmpresa');
    Route::get('soenac/resolucionPrueba/{id}', 'enterpriseController@resolucionPrueba');
    Route::get('soenac/facPruebas/{id}', 'enterpriseController@facPruebas');
    Route::get('/certificateUp/{id}', 'enterpriseController@certificateUp');
    Route::get('/enterpriseUpdate/{id}', 'enterpriseController@enterpriseUpdating');
    // Route::post('/soenac/resolutions', 'enterpriseController@resolutions');
    Route::post('/soenac/resolutions', 'enterpriseController@resolutions');
});
// ruta para token maestro
Route::group(['prefix' => 'generals'], function(){
    Route::apiResource('', 'generalController');
});

    Route::get('testing', 'enterpriseController@testing');



