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
//admin
Route::group(['middleware' => ['role:administrator']], function () {
    Route::get('/uzytkownicy', 'UsersController@workers')->name('uzytkownicy');
    Route::post('/createuser', 'UsersController@createuser');   
    Route::post('/deleteuser', 'UsersController@deleteuser');   
    Route::post('/edituser', 'UsersController@edituser');   
    Route::get('/users', 'UsersController@users');
});
//home
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/mojekonto', 'HomeController@myaccount')->name('moje konto');
//zmiana hasła
Route::post('/editpass', 'UsersController@editpass')->middleware('auth'); 
//moduł ekstraktor
Route::get('/ekstraktor', 'ExtractorController@index')->name('extractor');
Route::post('/uploadfile', 'AdminController@uploadfile');
Route::post('/createraport', 'ExtractorController@createraport');





