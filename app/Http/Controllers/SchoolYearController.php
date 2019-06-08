<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\SchoolYear;
use \App\Role;

class SchoolYearController extends Controller
{
    public $fields = ["semester" => "Semestre", "year" => "Ano"];
	private $paginate = 15;
	private $model = "\App\SchoolYear";

    public function index(Request $request)
    {
    	$model = new $this->model();

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = SchoolYear::orderBy('id', 'asc')->paginate($this->paginate);

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

        return view('school_year.index')->with($data);
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
	    			return redirect()->route('Período Letivo')->with('message-success', 'Período letivo inserido com sucesso!');
	    		} else {
	    			return redirect()->route('Período Letivo')->with('message-error', 'Erro ao inserir período letivo!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Período Letivo')->with('message-error', $e->getMessage());
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

        return view('school_year.create')->with($data);
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

    	return view('school_year.edit')->with($data);
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
	    			return redirect()->route('Período Letivo')->with('message-success', 'Período letivo atualizado com sucesso!');
	    		} else {
	    			return redirect()->route('Período Letivo')->with('message-error', 'Erro ao atualizar período letivo!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Período Letivo')->with('message-error', $e->getMessage());
    		}
    	}
    }

    public function delete($id = null) {
        $data = $this->model::find($id);
        
        try {
            if($data->delete()) {
                return redirect()->route('Período Letivo')->with('message-success', 'Período letivo deletado com sucesso!');
            } else {
                return redirect()->route('Período Letivo')->with('message-error', 'Erro ao deletar período letivo!');
            }
        } catch(Exception $e) {
            return redirect()->route('Período Letivo')->with('message-error', $e->getMessage());
        }
    }
}
