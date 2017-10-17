<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function customer()
    {
    	return $this->belongsTo('App\Model\Customer');
    }
}
