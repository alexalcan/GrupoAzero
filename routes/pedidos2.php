<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {

	Route::get('pedidos2/', 'Pedidos2Controller@index');
	Route::get('pedidos2/index', 'Pedidos2Controller@index');
	Route::get('pedidos2/lista', 'Pedidos2Controller@lista');

	Route::get('pedidos2/nuevo', 'Pedidos2Controller@nuevo');
	Route::post('pedidos2/guardar/{id}', 'Pedidos2Controller@guardar');
	Route::post('pedidos2/crear', 'Pedidos2Controller@crear');

	Route::get('pedidos2/pedido/{id}', 'Pedidos2Controller@pedido');
	Route::get('pedidos2/masinfo/{id}', 'Pedidos2Controller@masinfo');
	Route::get('pedidos2/historial/{id}', 'Pedidos2Controller@historial');
	Route::get('pedidos2/fragmento/{id}/{cual}', 'Pedidos2Controller@fragmento');

	Route::get('pedidos2/entregar_edit/{id}', 'Pedidos2Controller@entregar_edit');
	Route::get('pedidos2/set_accion_entregar/{id}', 'Pedidos2Controller@set_accion_entregar');
	Route::get('pedidos2/set_parcial_status/{id}', 'Pedidos2Controller@set_parcial_status');

	Route::get('pedidos2/parcial_nuevo/{id}', 'Pedidos2Controller@parcial_nuevo');	
	Route::post('pedidos2/parcial_crear/{id}', 'Pedidos2Controller@parcial_crear');
	Route::get('pedidos2/parcial_edit/{id}', 'Pedidos2Controller@parcial_edit');
	Route::post('pedidos2/parcial_update/{id}', 'Pedidos2Controller@parcial_update');
	Route::get('pedidos2/parcial_lista/{id}', 'Pedidos2Controller@parcial_lista');
	                            

	Route::get('pedidos2/subproceso_nuevo/{id}', 'Pedidos2Controller@subproceso_nuevo');
	Route::post('pedidos2/smaterial_crear/{id}', 'Pedidos2Controller@smaterial_crear');
	Route::get('pedidos2/smaterial_edit/{id}', 'Pedidos2Controller@smaterial_edit');
	Route::post('pedidos2/smaterial_update/{id}', 'Pedidos2Controller@smaterial_update');
	Route::get('pedidos2/smaterial_lista/{id}', 'Pedidos2Controller@smaterial_lista');
	Route::get('pedidos2/set_smaterial_status/{id}', 'Pedidos2Controller@set_smaterial_status');


	Route::post('pedidos2/ordenf_crear/{id}', 'Pedidos2Controller@ordenf_crear');
	Route::get('pedidos2/ordenf_edit/{id}', 'Pedidos2Controller@ordenf_edit');
	Route::post('pedidos2/ordenf_update/{id}', 'Pedidos2Controller@ordenf_update');
	Route::get('pedidos2/ordenf_lista/{id}', 'Pedidos2Controller@ordenf_lista');

	Route::post('pedidos2/requisicion_crear/{id}', 'Pedidos2Controller@requisicion_crear');
	Route::get('pedidos2/requisicion_edit/{id}', 'Pedidos2Controller@requisicion_edit');
	Route::post('pedidos2/requisicion_update/{id}', 'Pedidos2Controller@requisicion_update');
	Route::get('pedidos2/requisicion_lista/{id}', 'Pedidos2Controller@requisicion_lista');


	Route::get('pedidos2/devolucion_lista/{id}', 'Pedidos2Controller@devolucion_lista');
	Route::get('pedidos2/devolucion_edit/{id}', 'Pedidos2Controller@devolucion_edit');
	Route::post('pedidos2/devolucion_update/{id}', 'Pedidos2Controller@devolucion_update');
	Route::get('pedidos2/devolucion_nuevo/{order_id}', 'Pedidos2Controller@devolucion_nuevo');
	Route::post('pedidos2/devolucion_crear/{id}', 'Pedidos2Controller@devolucion_crear');


	Route::get('pedidos2/refacturacion_lista/{id}', 'Pedidos2Controller@refacturacion_lista');
	Route::get('pedidos2/refacturacion_edit/{id}', 'Pedidos2Controller@refacturacion_edit');
	Route::post('pedidos2/refacturacion_update/{id}', 'Pedidos2Controller@refacturacion_update');
	Route::get('pedidos2/refacturacion_nuevo/{order_id}', 'Pedidos2Controller@refacturacion_nuevo');
	Route::post('pedidos2/refacturacion_crear/{id}', 'Pedidos2Controller@refacturacion_crear');

	
	Route::get('pedidos2/shipment_edit/{id}', 'Pedidos2Controller@shipment_edit');
	Route::post('pedidos2/shipment_update/{id}', 'Pedidos2Controller@shipment_update');


	Route::get('pedidos2/accion/{id}', 'Pedidos2Controller@accion');
	Route::post('pedidos2/set_accion/{id}', 'Pedidos2Controller@set_accion');
	
	Route::get('pedidos2/attachlist', 'Pedidos2Controller@attachlist');
	Route::post('pedidos2/attachpost', 'Pedidos2Controller@attachpost');
	Route::get('pedidos2/attachdelete', 'Pedidos2Controller@attachdelete');


	Route::get('pedidos2/cancelar/{id}', 'Pedidos2Controller@cancelar');
	Route::get('pedidos2/descancelar/{id}', 'Pedidos2Controller@descancelar');
});