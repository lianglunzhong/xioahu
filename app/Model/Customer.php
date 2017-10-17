<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Answer;

class Customer extends Model
{
    public function answers() {
        return $this->belongsToMany('App\Model\Answer')->withPivot('vote')->withTimestamps();
    }

    public function questions() 
    {
    	return $this->hasMany('APP\Model\Question');
    }
}
