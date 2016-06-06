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
    	$this->navigation = (\Request::segment(2)) ?:null;
    	$this->subNavigation = (\Request::segment(3)) ?:null;
        $this->page = ($this->subNavigation) ?:$this->navigation;
        $this->admin = \Auth::user();

        view()->share([
    		'activeMenu' => $this->navigation,
    		'activeSubMenu' => $this->subNavigation,
            'isLogin' => \Auth::check(),
            'admin' => $this->admin
        ]);
    }

    public function getTable($columns, $tableUrl, $view = 'default_table', $tableName = null)
    {
        $tableName = ($tableName)?:$this->page;
        $datatableColumns = [];

        foreach ($columns as $column) {
            $datatableColumns[] = [
                'data' => $column,
                'name' => $column
            ];
        }

        \JavaScript::put([
            'datatable_columns' => $datatableColumns
        ]);

        return view('admin.default.' . $view, compact('datatableColumns', 'tableUrl', 'tableName'));
    }
}
