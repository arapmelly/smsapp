<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;

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
}
