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
Route::get('/admin/usuarios/materias/{id}', 'UsersController@materias')->name('Usuários - Matérias');
Route::post('/admin/usuarios/materias/{id}', 'UsersController@materias');
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
	Route::post('/permissoes/{id}/update', 'RolesController@update_permissions');
});


// Cursos - Admin
Route::prefix('admin/cursos')->group(function () {
	Route::get('/', 'CoursesController@index')->name('Cursos');
	Route::get('/inserir', 'CoursesController@create')->name('Cursos - Inserir');
	Route::post('/inserir', 'CoursesController@create');
	Route::get('/editar/{id}', 'CoursesController@edit')->name('Cursos - Editar');
	Route::post('/editar/{id}', 'CoursesController@update');
	Route::get('/deletar/{id}', 'CoursesController@delete')->name('Cursos - Deletar');
});


// Matérias - Admin
Route::prefix('admin/materias')->group(function () {
	Route::get('/', 'SubjectsController@index')->name('Matérias');
	Route::get('/inserir', 'SubjectsController@create')->name('Matérias - Inserir');
	Route::post('/inserir', 'SubjectsController@create');
	Route::get('/editar/{id}', 'SubjectsController@edit')->name('Matérias - Editar');
	Route::post('/editar/{id}', 'SubjectsController@update');
	Route::get('/deletar/{id}', 'SubjectsController@delete')->name('Matérias - Deletar');
});


// Período Letivo - Admin
Route::prefix('admin/periodo-letivo')->group(function () {
	Route::get('/', 'SchoolYearController@index')->name('Período Letivo');
	Route::get('/inserir', 'SchoolYearController@create')->name('Período Letivo - Inserir');
	Route::post('/inserir', 'SchoolYearController@create');
	Route::get('/editar/{id}', 'SchoolYearController@edit')->name('Período Letivo - Editar');
	Route::post('/editar/{id}', 'SchoolYearController@update');
	Route::get('/deletar/{id}', 'SchoolYearController@delete')->name('Período Letivo - Deletar');
});


// Notas - Docentes
Route::prefix('notas')->group(function () {
	Route::get('/', 'GradeController@index')->name('Notas');
	Route::post('/load_school_years', 'GradeController@load_school_years');
	Route::post('/load_subjects', 'GradeController@load_subjects');
	Route::post('/load_students', 'GradeController@load_students');
	Route::post('/save_grades', 'GradeController@update_grades');
});


// Materiais - Docentes
Route::prefix('materiais')->group(function () {
	Route::get('/', 'MaterialController@index')->name('Materiais');
	Route::post('/load_school_years', 'MaterialController@load_school_years');
	Route::post('/load_subjects', 'MaterialController@load_subjects');
	Route::post('/upload_file', 'MaterialController@upload_file');
	Route::post('/delete_file', 'MaterialController@delete_file');
});


// Estados
Route::prefix('admin/estados')->group(function () {
	Route::post('/cidades', 'StatesController@getCities');
});


Route::get('/login', function() {
	return view('login/index');
});
Auth::routes(['register' => false]);



