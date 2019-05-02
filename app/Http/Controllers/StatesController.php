<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatesController extends Controller
{
    public function getCities() {
        if(!empty($_POST)) {
            $state_id = $_POST["state_id"];

            try{
                $cities = \App\State::find($state_id)->cities;

                return json_encode(array("success" => true, "cities" => $cities));
            } catch(Exception $e) {
                return json_encode(array("success" => false));
            }
        }
    }
}
