<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;

class GradeController extends Controller
{
	private $model = "\App\Grade";

    public function index()
    {
        $data = new \App\Menu();
        $menus = $data->getMenus();
        
        $user_id = \Auth::user()->id;

        $user = \App\User::find($user_id);
        $role = $user->getRole()[0];

        if($role->role_id == 4) {
            $school_years = \DB::table("school_years as sy")
                            ->select("ss.school_year_id as id", \DB::raw("CONCAT(sy.year, '/', sy.semester) as name"))
                            ->join("student_subjects as ss", "ss.school_year_id", "=", "sy.id")
                            ->where("ss.user_id", "=", $user_id)
                            ->groupBy("ss.school_year_id")->groupBy("sy.year")->groupBy("sy.semester")
                            ->get();

            $data = [
                "menus" => $menus,
                "school_years" => $school_years
            ];

            return view('student.grades.index')->with($data);
        } else {
            $teacher_courses = \DB::table("users as u")
                            ->select("c.id as id", "c.name as name")
                            ->join("subjects as s", "u.id", "=", "s.teacher_id")
                            ->join("courses as c", "s.course_id", "=", "c.id")
                            ->where("u.id", "=", $user_id)
                            ->groupBy("c.id")
                            ->groupBy("c.name")
                            ->get();

            $data = [
                "menus" => $menus,
                "teacher_courses" => $teacher_courses
            ];

            return view('grades.index')->with($data);
        }
    }

    public function load_school_years() {
        $teacher_id = \Auth::user()->id;
        $course_id = $_POST["course_id"];

        $school_years = \DB::table("users as u")
                        ->select("sy.id as id", \DB::raw("CONCAT(sy.year, '/', sy.semester) as name"))
                        ->join("subjects as s", "u.id", "=", "s.teacher_id")
                        ->join("student_subjects as ss", "s.id", "=", "ss.subject_id")
                        ->join("school_years as sy", "ss.school_year_id", "=", "sy.id")
                        ->join("courses as c", "s.course_id", "=", "c.id")
                        ->where("u.id", "=", $teacher_id)
                        ->where("s.course_id", "=", $course_id)
                        ->groupBy("sy.id")->groupBy("sy.year")->groupBy("sy.semester")
                        ->get();
        
        echo json_encode($school_years);
        die();
    }

    public function load_subjects() {
        if($_POST["student"] == "true") {
            $school_year_id = $_POST["school_year_id"];

            $subjects = \DB::table("student_subjects as ss")
                        ->select("s.id as id", "s.name as name")
                        ->join("subjects as s", "s.id", "=", "ss.subject_id")
                        ->where([
                            ["ss.school_year_id", "=", $school_year_id],
                            ["ss.user_id", "=", \Auth::user()->id]
                        ])
                        ->get();

            $student = \App\Student::where("user_id", "=", \Auth::user()->id)->first();

            foreach ($subjects as $key => &$subject) {
                $grades = \DB::table("grades")
                            ->select("id", "value", "weight")
                            ->where("student_id", "=", $student->id)
                            ->where("school_year_id", "=", $school_year_id)
                            ->where("subject_id", "=", $subject->id)
                            ->get();
                $subject->grades = $grades;
            }
            
            echo json_encode($subjects);
            die();   
        } else {
            $teacher_id = \Auth::user()->id;
            $course_id = $_POST["course_id"];
            $school_year_id = $_POST["school_year_id"];

            $subjects = \DB::table("users as u")
                        ->select("s.id as id", "s.name as name")
                        ->join("subjects as s", "u.id", "=", "s.teacher_id")
                        ->join("student_subjects as ss", "s.id", "=", "ss.subject_id")
                        ->join("school_years as sy", "ss.school_year_id", "=", "sy.id")
                        ->where("u.id", "=", $teacher_id)
                        ->where("s.course_id", "=", $course_id)
                        ->where("ss.school_year_id", "=", $school_year_id)
                        ->groupBy("s.id")->groupBy("s.name")
                        ->get();

            echo json_encode($subjects);
            die();
        }
    }

    public function load_students() {
        $teacher_id = \Auth::user()->id;
        $course_id = $_POST["course_id"];
        $school_year_id = $_POST["school_year_id"];
        $subject_id = $_POST["subject_id"];

        $users = \DB::table("student_subjects as ss")
                    ->select("ss.user_id as id", "u.name as name", "u.email as email")
                    ->join("subjects as s", "s.id", "=", "ss.subject_id")
                    ->join("school_years as sy", "sy.id", "=", "ss.school_year_id")
                    ->join("courses as c", "c.id", "=", "s.course_id")
                    ->join("users as u", "u.id", "=", "ss.user_id")
                    ->where("s.course_id", "=", $course_id)
                    ->where("ss.school_year_id", "=", $school_year_id)
                    ->where("ss.subject_id", "=", $subject_id)
                    ->get();

        $students = [];
        foreach($users as $key => $user) {
            $student = \DB::table("students")->select("id", "course_id")->where("user_id", "=", $user->id)->get();
            $student = $student[0];
            $student->user_id = $user->id;
            $student->name = $user->name;
            $student->email = $user->email;

            // Load grades
            $grades = \DB::table("grades")
            ->where([
                ["student_id", "=", $student->id],
                ["school_year_id", "=", $school_year_id],
                ["subject_id", "=", $subject_id]
            ])
            ->select("value", "weight")
            ->get();
            
            $student->grades = $grades;
            $students[] = $student;
        }

        echo json_encode($students);
        die();
    }

    public function update_grades() {
        $school_year_id = $_POST["school_year_id"];
        $subject_id = $_POST["subject_id"];

        if(isset($_POST["value"]) && count($_POST["value"]) > 0) {
            $students = [];

            foreach($_POST["value"] as $key => $value) {
                $student_id = key($value);
                $grade_value = $value[$student_id];
                $weight = $_POST["weight"][$key][$student_id];
                
                $students[$student_id]["grades"][] = [
                    "value" => $grade_value,
                    "weight" => $weight
                ];
            }

            \App\Grade::where([
                ["school_year_id", "=", $school_year_id],
                ["subject_id", "=", $subject_id]
            ])->delete();

            foreach ($students as $key => $student) {
                $student_id = $key;
                foreach($student["grades"] as $grade) {
                    if($grade["value"] && $grade["weight"]) {
                        $data = new \App\Grade();
                        $data->student_id = $student_id;
                        $data->school_year_id = $school_year_id;
                        $data->subject_id = $subject_id;
                        $data->value = $grade["value"];
                        $data->weight = $grade["weight"];
                        $data->save();
                    }
                }
            }
        }

        return redirect()->route('Notas')->with('message-success', 'Notas cadastradas com sucesso!');
    }
}
