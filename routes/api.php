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

//~ Route::middleware('auth:api')->get('/user', function (Request $request) {
    //~ return $request->user();
//~ });

//~ Route::post('login', 'APILoginController@login');

//~ Route::middleware('jwt.auth')->get('users', function () {
	//~ return auth('api')->user();
//~ });

//~ Route::group(['middleware' => 'api', 'prefix' => 'auth'], function($router){

	//~ Route::post('register', 'APILoginController@register');
	//~ Route::post('login', 'APILoginController@login');
	//~ Route::post('logout', 'APILoginController@logout');
	//~ Route::post('refresh', 'APILoginController@refresh');
	//~ Route::post('me', 'APILoginController@me');
	
//~ });

Route::post('/register', 'APILoginController@register');

Route::post('/login', 'APILoginController@login');

Route::post('/me', 'APILoginController@me');

Route::post('/logout', 'APILoginController@logout');
