<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
    	parent::__construct();
    }

    public function getSignIn()
    {
    	return view('admin.signin');
    }
}
