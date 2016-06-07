<?php
namespace App\Helpers;

use App\User;


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

    public function adminUrlList()
    {
        $urlList = [];
        foreach (\Route::getRoutes() as $value) {
            $middlewares = $value->getAction()['middleware'];

            if (isset($middlewares) && is_array($middlewares) && in_array('restrictAccess', $middlewares)) {
                if (isset($value->getAction()['as'])) {
                    $name = explode('.', $value->getAction()['as'])[1];
                    $name = (strlen($name) < 4) ? strtoupper($name) : $name;

                    if ($name !== "" && !isset($urlList[$name]))
                        $urlList[$name] = url($value->getUri());
                }
            }
        }

        return $urlList;
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

    public function crudCheck($admin = null)
    {
        //check if alpha
        $superAllow = false;
        $admin = ($admin) ?: \Auth::user();

        if ($admin->roles()->first()->id == 1) {
            $superAllow = true;
        }

        //crud button permission check
        $action = \Route::getCurrentRoute()->getAction();
        $baseAction = str_replace('index', '', $action['as'], $count);
        $baseAction = ($count > 0) ? $baseAction : str_replace('ajax', '', $action['as'], $count);

        return [
            'create' => ($superAllow) ?: User::hasAccess($baseAction.'create', $admin),
            'edit' => ($superAllow) ?: User::hasAccess($baseAction.'edit', $admin),
            'destroy' => ($superAllow) ?: User::hasAccess($baseAction.'destroy', $admin),
        ];

    }

    public function editButton($url, $admin = null)
    {
        $allow = \Admin::crudCheck($admin);

        return ($allow['edit']) ? '<a href="'. $url .'" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';
    }

    public function deleteButton($url, $admin = null)
    {
        $allow = \Admin::crudCheck($admin);

        return ($allow['destroy']) ? view('admin.default.delete_button', compact('url')) : '';
    }
}
