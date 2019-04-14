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

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::get('/', function() { return redirect('dashboard'); });


// Admin
Route::get('/admin/menus', 'MenusController@index');
Route::post('/admin/menus/add', 'MenusController@add');


Route::get('/login', function() {
	return view('login/index');
});
Auth::routes(['register' => false]);



