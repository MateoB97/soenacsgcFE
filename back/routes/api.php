<?php

use Illuminate\Http\Request;

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

// Route::group(['prefix' => 'enterprises'], function(){
//     //Enterprises
//     Route::apiResource('adminEnterprise/{id?}', 'enterpriseController');

//     Route::get('soenacCampos', 'enterpriseController@soenacCampos');
// });

    Route::get('enterprises', 'enterpriseController@index');
    Route::get('enterprises/{id}', 'enterpriseController@show');
    Route::get('enterprises/soenac/soenacCampos', 'enterpriseController@soenacCampos');
    Route::get('enterprises/admin/adminEnterprises', 'enterpriseController@adminIndex');
    Route::get('enterprises/admin/showAdmin/{id}', 'enterpriseController@showAdmin');
    Route::post('enterprises', 'enterpriseController@store');
    Route::post('enterprises/soenac/softInfo/{id}', 'enterpriseController@softInfo');
    Route::post('enterprises/soenac/productionNumbers', 'enterpriseController@productionNumbers');
    Route::get('enterprises/admin/confirmEnterpriseDian/{id}', 'enterpriseController@confirmEnterpriseDian');
    Route::post('enterprises/soenac/resolutions', 'enterpriseController@resolutions');
    Route::put('enterprises/{id}', 'enterpriseController@update');
    Route::put('enterprises/certificateUp/{id}', 'enterpriseController@certificateUp');
    Route::put('enterprises/enterpriseUpdate/{id}', 'enterpriseController@enterpriseUpdate');
    Route::delete('enterprises/{id}', 'enterpriseController@Destroy');

    Route::get('/generals', 'generalController@index');
    Route::post('/generals', 'generalController@store');
    

    Route::get('testing', 'enterpriseController@testing');


    
