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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/mailing', 'HomeController@mailingTest')->name('mailing');

Route::post('/sharedObjects/{id}/addUser', 'SharedObjectController@addUser')->name('sharedObjects.addUser');
Route::get('/sharedObjects/{id}/removeUser/{userId}', 'SharedObjectController@removeUser')->name('sharedObjects.removeUser');
Route::get('/sharedObjects/{id}/reservation', 'ReservationController@create')->name('reservations.createFor');
Route::get('/myexport', 'HomeController@myExport')->name('myexport');
Route::get('/sharedObject/{id}/export', 'SharedObjectController@objectExport')->name('sharedObjectsExport');
Route::get('/sharedObject/{id}/myexport', 'SharedObjectController@myExport')->name('mySharedObjectExport');
Route::get('/reservations/search', 'ReservationController@search')->name('reservations.search');

Route::resources([
    'sharedObjects' => 'SharedObjectController',
    'reservations' => 'ReservationController',
    'templates' => 'TemplateController'
]);

