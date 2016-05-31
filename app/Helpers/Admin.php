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
}