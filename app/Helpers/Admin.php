<?php
namespace App\Helpers;


class Admin {

    public function adminRouteList()
    {
        $routeNameList = [];
        foreach (\Route::getRoutes() as $value) {
            // if (isset($value->getAction()['middleware']) && $value->getAction()['middleware'] == ['admin.auth', 'audit.report']) {
                if (isset($value->getAction()['as'])) {
                    $name = explode('.', $value->getAction()['as'])[1];
                    $name = (strlen($name) < 4) ? strtoupper($name) : $name;

                    if ($name !== "")
                        $routeNameList[$name][] = $value->getAction()['as'];
                }
            // }
        }

        return $routeNameList;
    }

    public function addButton($url)
    {
        return '<a href="'. $url .'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-erase"></i> Add</a>';
    }

    public function editButton($url)
    {
        return '<a href="'. $url .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';
    }

    public function deleteButton($url)
    {
        return view('admin.default.delete_button', compact('url'));
    }
}
