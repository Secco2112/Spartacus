<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
  	{
	    $role_administrator = Role::where('name', 'Administrador')->first();
	    $role_coordinator  = Role::where('name', 'Coordenador')->first();
	    $role_professor = Role::where('name', 'Professor')->first();
	    $role_student  = Role::where('name', 'Estudante')->first();

	    $employee = new User();
	    $employee->name = 'Administrador';
	    $employee->email = 'admin@gmail.com';
	    $employee->password = bcrypt('teste');
	    $employee->save();
	    $employee->roles()->attach($role_administrator);

	    $manager = new User();
	    $manager->name = 'Fábio Zanin';
	    $manager->email = 'faz@uricer.edu.br';
	    $manager->password = bcrypt('teste');
	    $manager->save();
	    $manager->roles()->attach($role_coordinator);

	    $manager = new User();
	    $manager->name = 'Marcos A. Lucas';
	    $manager->email = 'mlucas@uricer.edu.br';
	    $manager->password = bcrypt('teste');
	    $manager->save();
	    $manager->roles()->attach($role_professor);

	    $manager = new User();
	    $manager->name = 'Gustavo M. Marini';
	    $manager->email = 'gustavomarini9@gmail.com';
	    $manager->password = bcrypt('teste');
	    $manager->save();
	    $manager->roles()->attach($role_student);
  	}
}
