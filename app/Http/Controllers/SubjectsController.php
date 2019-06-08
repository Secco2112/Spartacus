<?php

namespace App\Http\Controllers;
use App\Subject;
use App\Role;

use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    public $fields = ["name" => "Nome"];
	private $paginate = 15;
	private $model = "\App\Subject";

    public function index(Request $request)
    {
    	$model = new $this->model();

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = Subject::orderBy('id', 'asc')->paginate($this->paginate);

        $role = new Role();
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

        return view('subjects.index')->with($data);
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
	    			return redirect()->route('Matérias')->with('message-success', 'Matéria inserida com sucesso!');
	    		} else {
	    			return redirect()->route('Matérias')->with('message-error', 'Erro ao inserir matéria!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Matérias')->with('message-error', $e->getMessage());
    		}
        }

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $courses = \App\Course::all();
        $teachers = \DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->select('users.*')->where("role_user.role_id", "2")->orWhere("role_user.role_id", "3")->get();

        $data = [
            "menus" => $menus,
            "courses" => $courses,
            "teachers" => $teachers
    	];

        return view('subjects.create')->with($data);
    }

    public function edit($id = null) {
    	$model = new $this->model;

    	$data = new \App\Menu();
        $menus = $data->getMenus();

		$dados = $model::find($id);
		
		$courses = \App\Course::all();
        $teachers = \DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->select('users.*')->where("role_user.role_id", "2")->orWhere("role_user.role_id", "3")->get();

    	$data = [
    		"menus" => $menus,
			"dados" => $dados,
			"courses" => $courses,
            "teachers" => $teachers
    	];

    	return view('subjects.edit')->with($data);
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
	    			return redirect()->route('Matérias')->with('message-success', 'Matéria atualizada com sucesso!');
	    		} else {
	    			return redirect()->route('Matérias')->with('message-error', 'Erro ao atualizar matéria!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Matérias')->with('message-error', $e->getMessage());
    		}
    	}
    }

    public function delete($id = null) {
        $data = $this->model::find($id);
        
        try {
            if($data->delete()) {
                return redirect()->route('Matérias')->with('message-success', 'Matéria deletada com sucesso!');
            } else {
                return redirect()->route('Matérias')->with('message-error', 'Erro ao deletar matéria!');
            }
        } catch(Exception $e) {
            return redirect()->route('Matérias')->with('message-error', $e->getMessage());
        }
    }
}
