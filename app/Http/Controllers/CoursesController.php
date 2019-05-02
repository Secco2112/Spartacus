<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Role;

class CoursesController extends Controller
{
    public $fields = ["name" => "Nome"];
	private $paginate = 15;
	private $model = "\App\Course";

    public function index(Request $request)
    {
    	$model = new $this->model();

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = Course::orderBy('id', 'asc')->paginate($this->paginate);

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

        return view('courses.index')->with($data);
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
	    			return redirect()->route('Cursos')->with('message-success', 'Curso inserido com sucesso!');
	    		} else {
	    			return redirect()->route('Cursos')->with('message-error', 'Erro ao inserir curso!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Cursos')->with('message-error', $e->getMessage());
    		}
        }

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $data = [
    		"menus" => $menus
    	];

        return view('courses.create')->with($data);
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

    	return view('courses.edit')->with($data);
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
	    			return redirect()->route('Cursos')->with('message-success', 'Curso atualizado com sucesso!');
	    		} else {
	    			return redirect()->route('Cursos')->with('message-error', 'Erro ao atulziar curso!');
	    		}
    		} catch(Exception $e) {
    			return redirect()->route('Cursos')->with('message-error', $e->getMessage());
    		}
    	}
    }

    public function delete($id = null) {
        $data = $this->model::find($id);
        
        try {
            if($data->delete()) {
                return redirect()->route('Cursos')->with('message-success', 'Curso deletado com sucesso!');
            } else {
                return redirect()->route('Cursos')->with('message-error', 'Erro ao deletar curso!');
            }
        } catch(Exception $e) {
            return redirect()->route('Cursos')->with('message-error', $e->getMessage());
        }
    }
}
