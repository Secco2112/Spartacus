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

Route::get('/dashboard', 'HomeController@index')->name('Dashboard');

Route::get('/', function() { return redirect('dashboard'); });


// Menus - Admin
Route::get('/admin/menus', 'MenusController@index')->name('Menus');
Route::post('/admin/menus/add', 'MenusController@add');
Route::post('/admin/menus/edit', 'MenusController@edit');
Route::post('/admin/menus/delete', 'MenusController@delete');


// Usuários Regras - Admin
Route::get('/admin/usuarios-regras', 'RolesController@index')->name('Usuários :: Regras');
Route::get('/admin/usuarios-regras/inserir', 'RolesController@create')->name('Usuários :: Regras - Inserir');
Route::get('/admin/usuarios-regras/editar/{id}', 'RolesController@edit')->name('Usuários :: Regras - Editar');
Route::post('/admin/usuarios-regras/editar/{id}', 'RolesController@update');


Route::get('/login', function() {
	return view('login/index');
});
Auth::routes(['register' => false]);



