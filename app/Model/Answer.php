<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Customer;

class Answer extends Model
{
    public function customers() 
    {
        return $this->belongsToMany('App\Model\Customer')->withPivot('vote')->withTimestamps();
    }

    public function customer()
    {
    	return $this->belongsTo('App\Model\Customer');
    }
}
