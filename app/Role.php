<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\VarDumper\VarDumper;

class Role extends Model
{
    public function users()
	{
  		return $this->belongsToMany(User::class);
	}

	public function hasPermissionTo($role) {
		$translatePermission = [
			"create" => "C",
			"edit" => "U",
			"delete" => "D"
		];

		$menu_data = \App\Menu::where("name", \Request::route()->getName())->get();
		$menu_id = $menu_data[0]->id;

		if(array_key_exists($role, $translatePermission)) {
			$current_user = \Auth::user();
        	$current_user_role = $current_user->getRole();

        	$permission = Permission::where([
        		["role_id", "=", $current_user_role[0]->id],
        		["menu_id", "=", $menu_id],
        		["option", "=", $translatePermission[$role]]
        	]);

        	return $permission->count() > 0;
		} else {
			return false;
		}
	}
}
