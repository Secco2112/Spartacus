<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

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
}
