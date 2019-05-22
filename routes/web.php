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
Route::prefix('admin/menus')->group(function () {
	Route::get('/', 'MenusController@index')->name('Menus');
	Route::post('/add', 'MenusController@add');
	Route::post('/edit', 'MenusController@edit');
	Route::post('/delete', 'MenusController@delete');
});


// Usuários - Admin
Route::get('/admin/usuarios', 'UsersController@index')->name('Usuários');
Route::get('/admin/usuarios/inserir', 'UsersController@create')->name('Usuários - Inserir');
Route::post('/admin/usuarios/inserir', 'UsersController@create');
Route::get('/admin/usuarios/editar/{id}', 'UsersController@edit')->name('Usuários - Editar');
Route::post('/admin/usuarios/editar/{id}', 'UsersController@update');
Route::get('/admin/usuarios/deletar/{id}', 'UsersController@delete')->name('Usuários - Deletar');
Route::post('/admin/usuarios/cep', 'UsersController@cep');


// Usuários Regras - Admin
Route::prefix('admin/usuarios-regras')->group(function () {
	Route::get('/', 'RolesController@index')->name('Usuários :: Regras');
	Route::get('/inserir', 'RolesController@create')->name('Usuários :: Regras - Inserir');
	Route::post('/inserir', 'RolesController@create');
	Route::get('/editar/{id}', 'RolesController@edit')->name('Usuários :: Regras - Editar');
	Route::post('/editar/{id}', 'RolesController@update');
	Route::get('/deletar/{id}', 'RolesController@delete')->name('Usuários :: Regras - Deletar');
    Route::get('/permissoes/{id}', 'RolesController@permissions')->name('Usuários :: Regras - Permissões');
});


// Cursos - Admin
Route::get('/admin/cursos', 'CoursesController@index')->name('Cursos');
Route::get('/admin/cursos/inserir', 'CoursesController@create')->name('Cursos - Inserir');
Route::post('/admin/cursos/inserir', 'CoursesController@create');
Route::get('/admin/cursos/editar/{id}', 'CoursesController@edit')->name('Cursos - Editar');
Route::post('/admin/cursos/editar/{id}', 'CoursesController@update');
Route::get('/admin/cursos/deletar/{id}', 'CoursesController@delete')->name('Cursos - Deletar');


// Estados
Route::post('/admin/estados/cidades', 'StatesController@getCities');


Route::get('/login', function() {
	return view('login/index');
});
Auth::routes(['register' => false]);



