<?php

namespace App\Http\Controllers;

use App\Material;
use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
use Symfony\Component\VarDumper\VarDumper;
use function GuzzleHttp\json_encode;
use Faker\Provider\zh_TW\DateTime;

class MaterialController extends Controller
{
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

            return view('student.materials.index')->with($data);
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

            return view('materials.index')->with($data);
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
            function human_filesize($bytes, $decimals = 2) {
                $sz = 'BKMGTP';
                $factor = floor((strlen($bytes) - 1) / 3);
                return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . " " . @$sz[$factor];
            }

            $school_year_id = $_POST["school_year_id"];
            
            $subjects = \DB::table("student_subjects as ss")
                        ->select("subject_id as id", "s.name as name")
                        ->join("subjects as s", "s.id", "=", "ss.subject_id")
                        ->where([
                            ["ss.user_id", "=", \Auth::user()->id],
                            ["ss.school_year_id", "=", $school_year_id]
                        ])
                        ->get();

            foreach ($subjects as &$subject) {
                $files = \DB::table("materials")
                        ->where([
                            ["school_year_id", "=", $school_year_id],
                            ["subject_id", "=", $subject->id]
                        ])
                        ->select("id", "filename", "path", "created_at")
                        ->get();
                
                $_files = [];
                if(count($files) > 0) {
                    foreach ($files as $file) {
                        $date = new \DateTime($file->created_at);

                        $_files[] = [
                            "id" => $file->id,
                            "filename" => $file->filename,
                            "path" => DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file->path,
                            "date" => $date->format("d/m/Y"),
                            "size" => human_filesize(filesize(public_path(DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR) . $file->path))
                        ];
                    }
                }

                $subject->files = $_files;
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

            foreach($subjects as &$subject) {
                $files = \DB::table("materials")
                        ->select("id", "filename", "path")
                        ->where([
                            ["school_year_id", "=", $school_year_id],
                            ["subject_id", "=", $subject->id]
                        ])
                        ->get();
                
                if(count($files) > 0) {
                    $subject->files_source = [];
                    foreach ($files as $key => $file) {
                        $subject->files_source[] = [
                            "id" => $file->id,
                            "source" => public_path(DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR) . $file->path,
                            "name" => $file->filename,
                            "size" => filesize(public_path(DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR) . $file->path)
                        ];
                    }
                }
                
                $subject->files = $files;
            }

            echo json_encode($subjects);
            die();
        }
    }

    public function upload_file() {
        $file = $_FILES["filepond"];
        $metadata = json_decode($_POST["filepond"]);
        $folder_to_upload = public_path(DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
        $file_name = md5($file['name'] . time()) . "_" . basename($file["name"]);
        $target = $folder_to_upload . $file_name;

        if(move_uploaded_file($file['tmp_name'], $target)) {
            $data = new \App\Material();
            $data->school_year_id = $metadata->school_year_id;
            $data->subject_id = $metadata->subject_id;
            $data->filename = basename($file["name"]);
            $data->path = $file_name;
            $data->save();
            
            die(true);
        } else {
            die(false);
        }
    }

    public function delete_file() {
        $id = $_POST["id"];
        $path = $_POST["path"];

        \App\Material::find($id)->delete();

        unlink($path);
    }
}
