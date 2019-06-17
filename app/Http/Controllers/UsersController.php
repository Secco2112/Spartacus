<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Mockery\Exception;
use App\Student;
use FlyingLuscas\ViaCEP\ZipCode;
use Symfony\Component\VarDumper\VarDumper;

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

        foreach ($dados as $key => &$user) {
            $role = $user->getRole();
            $user->role_id = $role[0]->role_id;
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
    		if($_POST["user_type_id"] == 4) {
                $data->name = $_POST["name"];
                $data->email = $_POST["email"];
                $data->password = bcrypt($_POST["password"]);
            }
    		try {
    			if($data->save()) {
                    if($_POST["user_type_id"] == 4) {
                        $user_id = $data->id;

                        $data = new Student();
                        $data->user_id = $user_id;
                        $data->academic_registry = str_pad($user_id, 6, "0", STR_PAD_LEFT);
                        $day_birth = substr($_POST["date_of_birth"], 0, 2);
                        $month_birth = substr($_POST["date_of_birth"], 3, 2);
                        $year_birth = substr($_POST["date_of_birth"], 6);
                        $data->date_of_birth = "$year_birth-$month_birth-$day_birth";
                        $data->city_id = $_POST["city_id"];
                        $data->state_id = $_POST["state_id"];
                        $data->mother_name = $_POST["mother_name"];
                        $data->mother_document = $_POST["mother_document"];
                        $data->father_name = $_POST["father_name"];
                        $data->father_document = $_POST["father_document"];
                        $data->zipcode = str_replace("-", "", $_POST["zipcode"]);
                        $data->address = $_POST["address"];
                        if(isset($_POST["no_number"])) {
                            $data->no_number = 1;
                        } else {
                            $data->number = $_POST["number"];
                        }
                        $data->neighborhood = $_POST["neighborhood"];
                        $data->complement = $_POST["complement"];
                        $data->cellphone = $_POST["cellphone"];
                        $data->phone = $_POST["phone"];
                        if($data->save()) {
                            return redirect()->route('Usuários')->with('message-success', 'Usuário inserido com sucesso!');
                        } else {
                            return redirect()->route('Usuários')->with('message-error', 'Erro ao inserir usuário!');
                        }
                    } else {
                        return redirect()->route('Usuários')->with('message-success', 'Usuário inserido com sucesso!');
                    }
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

        $states = \App\State::get();
        $cities = \App\City::get();
        $role = $dados->getRole();

        $student = [];
        if($role[0]->role_id == 4) {
            $student = \App\Student::where("user_id", $dados->id)->first();
        }

        $courses = \App\Course::all();

    	$data = [
    		"menus" => $menus,
            "dados" => $dados,
            "user_types" => $roles,
            "states" => $states,
            "cities" => $cities,
            "role" => $role,
            "student" => $student,
            "courses" => $courses
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

    public function cep() {
        if(!empty($_POST)) {
            $cep = $_POST["cep"];

            $zipcode = new ZipCode;
            $address = $zipcode->find($cep)->toArray();

            echo json_encode($address);
            die();
        }
    }

    public function materias($id) {
        if(!empty($_POST)) {
            \App\StudentSubject::where("user_id", $id)->delete();

            if(isset($_POST["school_year"]) && count($_POST["school_year"]) > 0) {
                foreach ($_POST["school_year"] as $key => $value) {
                    $data = new \App\StudentSubject();
                    $data->user_id = $id;
                    $data->subject_id = $_POST["subject"][$key];
                    $data->school_year_id = $value;
                    $data->save();
                }
            }
            return redirect()->route('Usuários')->with('message-success', 'Matérias cadastradas com sucesso!');
        }

        $model = new $this->model;

    	$data = new \App\Menu();
        $menus = $data->getMenus();

        $dados = $model::find($id);

        $role = $dados->getRole();

        $student = [];
        if($role[0]->role_id == 4) {
            $student = \App\Student::where("user_id", $id)->first();
        }

        $available_subjects = \App\Course::find($student->course_id)->subjects;
        $current_subjects = \DB::table("subjects")
                                ->join("student_subjects", "subjects.id", "=", "student_subjects.subject_id")
                                ->join("students", "students.user_id", "=", "student_subjects.user_id")
                                ->where("students.user_id", $id)
                                ->get();

        $school_years = \App\SchoolYear::orderBy("year", "ASC")->orderBy("semester", "ASC")->get();

    	$data = [
    		"menus" => $menus,
            "dados" => $dados,
            "role" => $role,
            "student" => $student,
            "available_subjects" => $available_subjects,
            "school_years" => $school_years,
            "current_subjects" => $current_subjects
    	];

    	return view('admin.users.subjects')->with($data);
    }
}
