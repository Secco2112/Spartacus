<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
  	{
    	$role_employee = new Role();
    	$role_employee->name = 'Administrador';
    	$role_employee->description = 'Usuário administrador do sistema.';
    	$role_employee->save();
    	$role_manager = new Role();
    	$role_manager->name = 'Coordenador';
    	$role_manager->description = 'Usuário coordenador de um curso.';
    	$role_manager->save();
    	$role_manager = new Role();
    	$role_manager->name = 'Professor';
    	$role_manager->description = 'Usuário professor de matérias de um curso.';
    	$role_manager->save();
    	$role_manager = new Role();
    	$role_manager->name = 'Estudante';
    	$role_manager->description = 'Usuário estudante de um curso.';
    	$role_manager->save();
  	}
}
