<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {

	Route::get('pedidos2/', 'Pedidos2Controller@index');
	Route::get('pedidos2/index', 'Pedidos2Controller@index');
	Route::get('pedidos2/lista', 'Pedidos2Controller@lista');

	Route::get('pedidos2/pedido/{id}', 'Pedidos2Controller@pedido');
	//Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	//Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    //Route::resource('users', 'UserController');


});