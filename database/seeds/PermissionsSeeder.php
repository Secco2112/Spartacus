<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = new Permission();
        $permission->role_id = 1;
        $permission->menu_id = 3;
        $permission->option = "C";
		$permission->save();

		$permission = new Permission();
        $permission->role_id = 1;
        $permission->menu_id = 3;
		$permission->option = "U";
		$permission->save();

		$permission = new Permission();
        $permission->role_id = 1;
        $permission->menu_id = 3;
		$permission->option = "D";
		$permission->save();
    }
}
