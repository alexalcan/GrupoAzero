<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('homesearch','WelcomeController@search')->name('homesearch');

//Auth::routes();

Route::get('home', 'HomeController@index')->name('home');
Route::get('home/prueba', 'HomeController@prueba');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::resource('users', 'UserController');
    Route::resource('orders', 'OrderController');
    Route::resource('check', 'CheckOrderController');
    Route::resource('archived', 'ArchivedController');
    Route::resource('roles', 'RoleController');
    Route::resource('departments', 'DepartmentController');
    Route::resource('picture', 'PictureController');
    Route::resource('follows', 'FollowsController');
    Route::resource('cancelations', 'CancelationsController');
    Route::resource('rebillings', 'RebillingsController');
    Route::resource('debolutions', 'DebolutionsController');
    Route::resource('shipments', 'ShipmentsController');

    Route::get('search','HomeController@search')->name('search');

    Route::get('picture','HomeController@picture')->name('picture'); // Foto de entregado
    Route::get('cancelation','HomeController@cancelation')->name('cancelation');

    // Rutas cancelación, refacturación y devolución
    Route::get('shipmentEvidence','ShipmentsController@shipmentEvidence')->name('shipments.evidence');

    Route::get('cancelEvidence','CancelationsController@cancelEvidence')->name('cancelations.evidence');
    Route::get('cancelRepayment','CancelationsController@cancelRepayment')->name('cancelations.repayment');

    Route::get('rebillingEvidence','RebillingsController@rebilEvidence')->name('rebillings.evidence');
    Route::get('rebillingRepayment','RebillingsController@rebilRepayment')->name('rebillings.repayment');

    Route::get('debolutionsEvidence','DebolutionsController@debolutionsEvidence')->name('debolutions.evidence');
    Route::get('debolutionsRepayment','DebolutionsController@debolutionsRepayment')->name('debolutions.repayment');
    
    
    Route::get('reportes','ReportesController@index')->name('reportes');
    Route::post('reportes/reporte','ReportesController@reporte')->name('reporte');
    
    
    Route::get('order/attachlist', 'OrderController@attachlist')->name("attachlist");
    Route::post('order/attachpost', 'OrderController@attachpost');
    Route::get('order/attachdev', 'OrderController@attachdev');
    Route::get('order/attachdelete', 'OrderController@attachdelete');
    Route::get("order/debolutioncreatefor", "OrderController@debolutioncreatefor");
    Route::get("order/partialcreatefor", "OrderController@partialcreatefor");
    
});

require_once "pedidos2.php";

// Para generar el storage link
// Route::get('storage-link', function(){
//     Artisan::call('storage:link');
// });
