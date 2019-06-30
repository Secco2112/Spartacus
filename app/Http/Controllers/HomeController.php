<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        #$request->user()->authorizeRoles(['Administrador', 'Coordenador', 'Professor', 'Estudante']);

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

        

        if(\Auth::check()) {
            $current_user = \Auth::user();
            $user = \App\User::find($current_user->id);
            $role = $user->getRole();
            var_dump($role);
            if($role[0]->role_id == 1) {
                return redirect('/admin/cursos')->with(['menus' => $menus]);
            } else {
                return redirect('/notas')->with(['menus' => $menus]);
            }
        } else {
            return view('index', ['menus' => $menus]);
        }
    }
}
