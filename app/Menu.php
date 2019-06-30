<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

	protected $table = 'menus';


	public function getMenus() {
		$current_user = \Auth::user();
        $current_user_role = $current_user->getRole();
        
        $all_menus = \DB::table('menus')->orderBy('parent_id', 'asc')->get();
        $menus = [];

        function funRecursiveMenu($arr, $parent = 0) {
            $menus = [];
            foreach($arr as $menu) {
                if($menu->parent_id == $parent) {
                    $menu->children = isset($menu->children) ? $menu->children : funRecursiveMenu($arr, $menu->id);
                    $menus[] = $menu;
                }
            }
            return $menus;
        }

        $menus = funRecursiveMenu($all_menus);

        // Get allowed menus
        function setMenusPermission($arr, $role) {
            foreach ($arr as &$menu) {
                $permissions = \App\Permission::where([
                    ['role_id', '=', $role->role_id],
                    ['menu_id', '=', $menu->id]
                ])->get();
                $menu->count_permission = $permissions->count();

                if(count($menu->children)) {
                    setMenusPermission($menu->children, $role);
                }
            }
        }

        setMenusPermission($menus, $current_user_role[0]);

        return $menus;
	}
    
}
