<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
    	$this->admin = 'admin';

    	$this->navigation = (\Request::segment(2)) ?:null;
    	$this->subNavigation = (\Request::segment(3)) ?:null;

    	view()->share([
    		'activeMenu' => $this->navigation,
    		'activeSubMenu' => $this->subNavigation,
            'isLogin' => \Auth::check()
    	]);
    }
}
