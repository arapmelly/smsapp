<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //


    public function contacts(){

    	return $this->hasMany('App\Contact');
    }


    public function bulks(){

    	return $this->hasMany('App\Bulk');
    }
}
