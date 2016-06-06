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

    public function getTable($columns, $url, $view = 'default_table', $currentPage = null)
    {
        $currentPage = ($currentPage)?:$this->page;
        $datatableColumns = [];

        foreach ($columns as $column) {
            $datatableColumns[] = [
                'data' => $column,
                'name' => $column
            ];
        }

        $datatableColumns[] = ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false];

        \JavaScript::put([
            'datatable_columns' => $datatableColumns
        ]);

        return view('admin.default.' . $view, compact('datatableColumns', 'url', 'currentPage'));
    }

    public function getForm($query, $formAttr)
    {
        $url = (isset($formAttr['url'])) ? $formAttr['url'] : '#';
        $view = (isset($formAttr['view'])) ? $formAttr['view'] : 'default_form';
        $method = (isset($formAttr['method'])) ? $formAttr['method'] : 'post';
        $currentPage = (isset($formAttr['currentPage'])) ? $formAttr['currentPage'] : $this->page;
        $fields = (isset($formAttr['fields'])) ? $formAttr['fields'] : null;

        return view('admin.default.' . $view, compact('query', 'url', 'currentPage', 'method', 'fields'));
    }
}
