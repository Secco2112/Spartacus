<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Symfony\Component\VarDumper\VarDumper;

class RolesController extends Controller
{

	public $fields = ["name" => "Nome", "description" => "Descrição"];
	private $paginate = 15;
	private $model = "\App\Role";

    public function index() {
		$model = new $this->model();

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = Role::orderBy('id', 'asc')->paginate($this->paginate);

        $buttons = [
        	"create" => false,
        	"edit" => false,
        	"delete" => false
        ];
        foreach ($buttons as $key => $value) {
        	$buttons[$key] = $model->hasPermissionTo($key);
        }

        $data = [
        	"menus" => $menus,
        	"fields" => $this->fields,
        	"dados" => $dados,
        	"buttons" => $buttons
        ];

    	return view('admin.roles.index')->with($data);
	}
	
	public function create() {
        if(!empty($_POST)) {
            $data = new $this->model;
    		foreach ($_POST as $key => $value) {
    			if($key != "_token")
    				$data->{$key} = $value;
    		}
    		try {
    			if($data->save()) {
	    			return redirect()->route('Usuários :: Regras')->with('message-success', 'Regra inserida com sucesso!');
	    		} else {
	    			return redirect()->route('Usuários :: Regras')->with('message-error', 'Erro ao inserir regra!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Usuários :: Regras')->with('message-error', $e->getMessage());
    		}
        }

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $data = [
    		"menus" => $menus
    	];

        return view('admin.roles.create')->with($data);
    }

    public function edit($id = null) {
    	$model = new $this->model;

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = $model::find($id);

    	$data = [
    		"menus" => $menus,
    		"dados" => $dados
    	];

    	return view('admin.roles.edit')->with($data);
    }

    public function update(Request $request, $id) {
    	if(!empty($_POST)) {
    		$data = $this->model::find($id);
    		foreach ($_POST as $key => $value) {
    			if($key != "_token")
    				$data->{$key} = $value;
    		}
    		try {
    			if($data->save()) {
	    			return redirect()->route('Usuários :: Regras')->with('message-success', 'Regra atualizada com sucesso!');
	    		} else {
	    			return redirect()->route('Usuários :: Regras')->with('message-error', 'Erro ao atulziar regra!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Usuários :: Regras')->with('message-error', $e->getMessage());
    		}
    		
    	}
	}
	
	public function delete($id = null) {
        $data = $this->model::find($id);
        
        try {
            if($data->delete()) {
                return redirect()->route('Usuários :: Regras')->with('message-success', 'Regra deletada com sucesso!');
            } else {
                return redirect()->route('Usuários :: Regras')->with('message-error', 'Erro ao deletar regra!');
            }
        } catch(Exception $e) {
            return redirect()->route('Usuários :: Regras')->with('message-error', $e->getMessage());
        }
	}
	
	public function permissions($id = null) {
    	$data = new \App\Menu();
		$menus = $data->getMenus();
		
		$dados = [];

		$all_menus = \App\Menu::all();
		foreach($all_menus as $i => $menu) {
			$permissions = \App\Permission::where([
				["role_id", "=", $id],
				["menu_id", "=", $menu->id]
			])->get();
			$dados[$i]["menu"]["id"] = $menu->id;
			$dados[$i]["menu"]["name"] = $menu->name;
			$dados[$i]["permissions"] = [];
			foreach($permissions as $j => $perm) {
				$dados[$i]["permissions"][] = $perm->option;
			}
		}

		$permissions = ["C"=>"Inserir", "U"=>"Alterar", "D"=>"Deletar"];

    	$data = [
    		"menus" => $menus,
			"dados" => $dados,
			"permissions" => $permissions
    	];

    	return view('admin.roles.permissions')->with($data);
	}
}
