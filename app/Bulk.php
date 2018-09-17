<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Group;
use App\Bulk;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use AfricasTalking\SDK\AfricasTalking;

class Bulk extends Model
{
    //

    public function group(){

    	return $this->belongsTo('App\Group');
    }


    public static function setTime($time){

    	$tm = explode(':', $time);

    	$hour = $tm[0];
    	$min = $tm[1];
    	$sec = $tm[2];

    	$newsec = "00";

    	$newtime = $hour.':'.$min.':'.$newsec;

    	return $newtime;
    }


    public static function getContacts($id){

    	//get all contacts belonging to this group
    	$group = Group::find($id);

    	//$contacts = DB::table('contacts')->where('group_id', '=', $id)->pluck('phone');
    	$conts = $group->contacts;
    	
    	$cs = $conts->implode('phone', ',');

    	

    	return $cs;
    }


    public static function sendBulk($id, $contacts, $text){



    	$username = 'kemstwo';
        $apiKey     = '1f838716c74f469ebcb5a9ac1c9c1de28dd82a12cd9b4251a0c14d8efa2c4e0f'; 
        
        $AT = new AfricasTalking($username, $apiKey);

        $SMS = $AT->sms();

        $options = [
            'message'=>$text,
            'to'=>$contacts,
            'enqueue'=>true
        ];

        $response = $SMS->send($options);

       Log::info($response);

        if($response['status'] == 'success'){

            $ob = Bulk::find($id);
            $ob->is_send = true;
            $ob->update();

            return ['status'=>true];

        } else {

             return ['status'=>false];
        }
        


    }
    
}
