<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function validateTest(Request $request) {
    	$this->validate($request, [
    		'name' => 'required',
    		'phone' => 'required',
    		'email' => 'required',
    	]);

    	return ['status' => 1, 'mes' => 'success'];
    }
}
