<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $create = ['index', 'create', 'store', 'ajax'];
    protected $edit = ['index', 'edit', 'update', 'ajax'];
    protected $delete = ['index', 'destroy', 'ajax'];
    protected $defaultRoute = 'admin..index'; //default route for all roles
    protected $ignoredActionRoute = ['index', 'ajax'];
    protected $defaultSettingRoute = 'admin.setting.account.index';

    public static function saveThese($request, $id = null)
    {
        $row = ($id) ? self::findOrFail($id) : new self;

        $row->name = $request->name;
        $row->slug = $request->slug;
        $row->parent_role_id = ($request->parent_role_id > 0) ?: \Auth::user()->roles()->first()->id;

        $row->whitelisted_ip_addresses = $request->whitelisted_ip_addresses;

        $routes = call_user_func_array('array_merge', \Admin::adminRouteList());
        $permissions = [];

        if (isset($request->allow_all['all'])) {
            foreach ($routes as $route) {
                $permissions[$route] = true;
            }
        } else {
            $permissions = self::permissionEncodes($request, 'create');
            $permissions += self::permissionEncodes($request, 'edit');
            $permissions += self::permissionEncodes($request, 'delete');
            $permissions += self::permissionEncodes($request, 'ajax');
        }

        $row->permissions = json_encode($permissions);

        return $row->save();
    }

    public static function permissionEncodes($request, $action)
    {
        $staticVar = new static;

        $routes = call_user_func_array('array_merge', \Admin::adminRouteList());

        $encodedPermissions = [];
        if ($request->get($action)) {
            foreach ($request->get($action) as $key => $value) {
                foreach ($routes as $route) {
                    $routeSections = explode('.', $route);
                    $getActionName = end($routeSections);
                    $sectionLevel = count($routeSections) - 1;

                    $name = $routeSections[1];

                    $sectionName = '';
                    for ($i=1; $i < $sectionLevel; $i++) {
                        $sectionName .= ($i>1) ? '.' : '';
                        $sectionName .= $routeSections[$i];
                    }

                    if ($sectionName == strtolower($key) && in_array($getActionName, $staticVar->{$action})) {
                        $encodedPermissions[$route] = true;
                    }
                }
            }

            $encodedPermissions[$staticVar->defaultRoute] = true;
            $encodedPermissions[$staticVar->defaultSettingRoute] = true;
        }

        return $encodedPermissions;
    }

    public static function permissionDecodes($permissions)
    {
        $staticVar = new static;

        $permissions = json_decode($permissions, true);
        $permissions = ($permissions) ? $permissions : [];

        $decodedPermissions = [];

        foreach ($permissions as $key => $value) {
            $routeSections = explode('.', $key);
            $getActionName = end($routeSections);
            $sectionLevel = count($routeSections) - 1;

            $name = $routeSections[1];

            $sectionName = '';
            for ($i=1; $i < $sectionLevel; $i++) {
                $sectionName .= ($i>1) ? '.' : '';
                $sectionName .= $routeSections[$i];
            }

            if ($sectionName !== "") {
                if (in_array($getActionName, $staticVar->create) && !in_array($getActionName, $staticVar->ignoredActionRoute)) {
                    $decodedPermissions['create.'.$sectionName] = 1;
                } elseif (in_array($getActionName, $staticVar->edit)  && !in_array($getActionName, $staticVar->ignoredActionRoute)) {
                    $decodedPermissions['edit.'.$sectionName] = 1;
                } elseif (in_array($getActionName, $staticVar->delete)  && !in_array($getActionName, $staticVar->ignoredActionRoute)) {
                    $decodedPermissions['delete.'.$sectionName] = 1;
                }
            }
        }

        return $decodedPermissions;
    }
}
