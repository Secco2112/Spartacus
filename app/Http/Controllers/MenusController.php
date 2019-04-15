<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenusController extends Controller
{
    public function index(Request $request)
    {
    	$data = new \App\Menu();
        $menus = $data->getMenus();

        return view('admin.menus.index', ["menus" => $menus]);
    }


    public function add() {
        $menu_name = $_POST["menu_name"];
        $parent_id = $_POST["parent_id"];
        $menu_link = $_POST["menu_link"];

        $check = \App\Menu::where([
            ['parent_id', '=', $parent_id],
            ['name', '=', $menu_name]
        ])->get();
        
        if($check->count() == 0) {
            $data = new \App\Menu();
            $data->parent_id = $parent_id;
            $data->name = $menu_name;
            $data->url = $menu_link;
            $data->show_in_menu = 1;
            if($data->save()) {
                echo json_encode(array("success" => true, "message" => "Menu inserido com sucesso", "inserted_id" => $data->id));
            } else {
                echo json_encode(array("success" => false, "message" => "Erro inesperado ao inserir menu."));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Esse menu já possui um submenu com esse nome."));
        }
        die();
    }

    public function edit() {
        $menu_id = $_POST["menu_id"];
        $menu_name = $_POST["menu_name"];
        
        $data = \App\Menu::find($menu_id);
        $data->name = $menu_name;
        if($data->save()) {
            echo json_encode(array("success" => true, "message" => "Menu editado com sucesso"));
        } else {
            echo json_encode(array("success" => false, "message" => "Erro inesperado ao editar menu."));
        }
        die();
    }

    public function delete() {
        $menu_id = $_POST["menu_id"];
        
        $data = \App\Menu::find($menu_id);
        if($data->delete()) {
            echo json_encode(array("success" => true, "message" => "Menu deletado com sucesso"));
        } else {
            echo json_encode(array("success" => false, "message" => "Erro inesperado ao deletar menu."));
        }
        die();
    }
}
