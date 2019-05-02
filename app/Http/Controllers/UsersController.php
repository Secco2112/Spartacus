<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Mockery\Exception;

class UsersController extends Controller
{
    public $fields = ["name" => "Nome"];
	private $paginate = 15;
    private $model = "\App\User";
    
    public function index(Request $request)
    {
    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = User::orderBy('id', 'asc')->paginate($this->paginate);

        $role = new \App\Role();
        $buttons = [
        	"create" => false,
        	"edit" => false,
        	"delete" => false
        ];
        foreach ($buttons as $key => $value) {
        	$buttons[$key] = $role->hasPermissionTo($key);
        }

        $data = [
        	"menus" => $menus,
        	"fields" => $this->fields,
        	"dados" => $dados,
        	"buttons" => $buttons
        ];

        return view('admin.users.index')->with($data);
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
	    			return redirect()->route('Usuários')->with('message-success', 'Usuário inserido com sucesso!');
	    		} else {
	    			return redirect()->route('Usuários')->with('message-error', 'Erro ao inserir usuário!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Usuários')->with('message-error', $e->getMessage());
    		}
        }

        $roles = \App\Role::get();
        $states = \App\State::get();
        $cities = \App\City::get();

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $data = [
            "menus" => $menus,
            "user_types" => $roles,
            "states" => $states,
            "cities" => $cities
    	];

        return view('admin.users.create')->with($data);
    }

    public function edit($id = null) {
    	$model = new $this->model;

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = $model::find($id);

        $roles = \App\Role::get();

    	$data = [
    		"menus" => $menus,
            "dados" => $dados,
            "user_types" => $roles
    	];

    	return view('admin.users.edit')->with($data);
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
	    			return redirect()->route('Usuários')->with('message-success', 'Usuário atualizado com sucesso!');
	    		} else {
	    			return redirect()->route('Usuários')->with('message-error', 'Erro ao atulziar usuário!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Usuários')->with('message-error', $e->getMessage());
    		}
    	}
    }

    public function delete($id = null) {
        $data = $this->model::find($id);
        
        try {
            if($data->delete()) {
                return redirect()->route('Usuários')->with('message-success', 'Usuário deletado com sucesso!');
            } else {
                return redirect()->route('Usuários')->with('message-error', 'Erro ao deletar usuário!');
            }
        } catch(Exception $e) {
            return redirect()->route('Usuários')->with('message-error', $e->getMessage());
        }
    }
}
