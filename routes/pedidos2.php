<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {

	Route::get('pedidos2/', 'Pedidos2Controller@index');
	Route::get('pedidos2/index', 'Pedidos2Controller@index');
	Route::get('pedidos2/lista', 'Pedidos2Controller@lista');

	Route::get('pedidos2/nuevo', 'Pedidos2Controller@nuevo');
	Route::post('pedidos2/guardar', 'Pedidos2Controller@guardar');
	Route::post('pedidos2/crear', 'Pedidos2Controller@crear');

	Route::get('pedidos2/pedido/{id}', 'Pedidos2Controller@pedido');
	Route::get('pedidos2/masinfo/{id}', 'Pedidos2Controller@masinfo');


	Route::get('pedidos2/parcial_accion/{id}', 'Pedidos2Controller@parcial_accion');
	Route::post('pedidos2/set_accion/{id}', 'Pedidos2Controller@set_accion');
	Route::get('pedidos2/attachlist', 'Pedidos2Controller@attachlist');
	Route::post('pedidos2/attachpost', 'Pedidos2Controller@attachpost');
	Route::get('pedidos2/attachdelete', 'Pedidos2Controller@attachdelete');



});