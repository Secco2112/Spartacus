<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

		if(array_key_exists($role, $translatePermission)) {
			$current_user = \Auth::user();
        	$current_user_role = $current_user->getRole();

        	$permission = Permission::where([
        		["role_id", "=", $current_user_role[0]->id],
        		["menu_id", "=", 4],
        		["option", "=", $translatePermission[$role]]
        	]);

        	return $permission->count() > 0;
		} else {
			return false;
		}
	}
}
