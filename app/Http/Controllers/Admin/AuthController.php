<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\SignInRequest;
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

    public function postSignIn(SignInRequest $request)
    {

        if (!\Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect('admin/signin');
        }

        return redirect('admin/dashboard');
    }

    public function getSignOut()
    {
        \Auth::logout();

        return redirect('admin/signin');
    }
}
