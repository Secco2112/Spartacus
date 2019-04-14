<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenusController extends Controller
{
    public function index(Request $request)
    {
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
                    ['role_id', '=', $role->id],
                    ['menu_id', '=', $menu->id]
                ])->get();
                $menu->count_permission = $permissions->count();

                if(count($menu->children)) {
                    setMenusPermission($menu->children, $role);
                }
            }
        }

        setMenusPermission($menus, $current_user_role[0]);

        return view('admin.menus.index', ["menus" => $menus]);
    }


    public function add() {
        $menu_name = $_POST["menu_name"];
        $parent_id = $_POST["parent_id"];
        $menu_link = $_POST["menu_link"];

        $data = new \App\Menu();
        $data->parent_id = $parent_id;
        $data->name = $menu_name;
        $data->url = $menu_link;
        $data->show_in_menu = 1;
        $data->save();
    }
}
