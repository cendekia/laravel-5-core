<?php
namespace App\Helpers;


class Admin {

    public function adminRouteList()
    {
        $routeNameList = [];
        foreach (\Route::getRoutes() as $value) {
            $middlewares = $value->getAction()['middleware'];

            if (isset($middlewares) && is_array($middlewares) && in_array('restrictAccess', $middlewares)) {
                if (isset($value->getAction()['as'])) {
                    $name = explode('.', $value->getAction()['as'])[1];
                    $name = (strlen($name) < 4) ? strtoupper($name) : $name;

                    if ($name !== "")
                        $routeNameList[$name][] = $value->getAction()['as'];
                }
            }
        }

        return $routeNameList;
    }

    public function isHasAccess($routes, $user = null)
    {
        $hasAccess = false;
        $user = ($user) ?:\Auth::user();
        $role = $user->roles()->first();

        foreach ($routes as $route) {
            $hasAccess = \App\User::hasAccess($route, $user);

            if ($hasAccess == true) break;
        }

        return ($role->id == 1) ? true : $hasAccess;
    }

    public function editButton($url)
    {
        return '<a href="'. $url .'" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';
    }

    public function deleteButton($url)
    {
        return view('admin.default.delete_button', compact('url'));
    }
}
