<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

    public function getTable($columns, $url, $view = 'default_table', $pageTitle = null, $parentTable = null)
    {
        $admin = $this->admin;
        $pageTitle = ($pageTitle)?:$this->page;
        $datatableColumns = [];

        foreach ($columns as $column) {
            $split = explode('.', $column);

            $data = (reset($split) == $parentTable) ? end($split) : reset($split);

            $datatableColumns[] = [
                'data' => $data,
                'name' => $column
            ];
        }

        $datatableColumns[] = ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false];

        \JavaScript::put([
            'datatable_columns' => $datatableColumns
        ]);

        $actionButtons = \Admin::crudCheck($admin);

        return view('admin.default.' . $view, compact('datatableColumns', 'url', 'pageTitle', 'actionButtons'));
    }

    public function getForm($query, $formAttr, $data = [])
    {
        $url = (isset($formAttr['url'])) ? $formAttr['url'] : '#';
        $view = (isset($formAttr['view'])) ? $formAttr['view'] : 'default_form';
        $method = (isset($formAttr['method'])) ? $formAttr['method'] : 'post';
        $pageTitle = (isset($formAttr['pageTitle'])) ? $formAttr['pageTitle'] : $this->page;
        $fields = (isset($formAttr['fields'])) ? $formAttr['fields'] : null;

        return view('admin.default.' . $view, compact('query', 'url', 'pageTitle', 'method', 'fields', 'data'));
    }
}
