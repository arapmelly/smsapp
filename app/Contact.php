<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    //


    public function group(){

    	return $this->belongsTo('App\Group');
    }


    public static function formatPhone($number){

    	//get the last nine characters
    	$phone_numbers = substr($number, -9);

    	

    	//add 254 at the front
    	$phone_number = '254'.''.$phone_numbers;

    

    	return $phone_number;
    }


    public static function isExist($phone){

        $phone_number = Client::formatPhone($phone);

        $exists = DB::table('contacts')->where('phone', '=', $phone_number)->count();

        if($exists > 0){
            return true;
        } else {
            return false;
        }
    }
}
