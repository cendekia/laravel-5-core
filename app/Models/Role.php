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

    protected $create = ['index', 'create', 'store'];
    protected $edit = ['index', 'edit', 'update'];
    protected $delete = ['index', 'destroy'];
    protected $defaultRoute = 'admin..index'; //default route for all roles

    public static function saveThese($request, $id = null)
    {
        $row = ($id) ? self::findOrFail($id) : new self;

        $row->name = $request->name;
        $row->slug = $request->slug;

        $routes = call_user_func_array('array_merge', \Admin::adminRouteList());

        $permissions = [];

        if (isset($request->allow_all['all']))
        {
            foreach ($routes as $route)
            {
                $permissions[$route] = true;
            }
        }
        else
        {
            $permissions = self::permissionEncodes($request, 'create');
            $permissions += self::permissionEncodes($request, 'edit');
            $permissions += self::permissionEncodes($request, 'delete');
        }

        $row->permissions = json_encode($permissions);

        return $row->save();
    }

    public static function permissionEncodes($request, $action)
    {
        $staticVar = new static;

        $routes = call_user_func_array('array_merge', \Admin::adminRouteList());

        $encodedPermissions = [];

        if ($request->get($action))
        {
            foreach ($request->get($action) as $key => $value)
            {
                foreach ($routes as $route)
                {
                    $getAttributes = explode('.', $route);
                    $getSectionName = $getAttributes[1];
                    $getActionName = end($getAttributes);

                    if ($getSectionName == strtolower($key) && in_array($getActionName, $staticVar->{$action}))
                    {
                        $encodedPermissions[$route] = true;
                    }
                }
            }

            $encodedPermissions[$staticVar->defaultRoute] = true;
        }

        return $encodedPermissions;
    }

    public static function permissionDecodes($permissions)
    {
        $staticVar = new static;

        $permissions = json_decode($permissions, true);
        $permissions = ($permissions) ? $permissions : [];

        $decodedPermissions = [];

        foreach ($permissions as $key => $value)
        {
            $getAttributes = explode('.', $key);
            $getActionName = end($getAttributes);
            $getSectionName = $getAttributes[1];

            $getSectionName = (strlen($getSectionName) < 4) ? strtoupper($getSectionName) : $getSectionName;

            if ($getSectionName !== "") {
                if (in_array($getActionName, $staticVar->create) && $getActionName != 'index') {
                    $decodedPermissions['create.'.$getSectionName] = 1;
                } elseif (in_array($getActionName, $staticVar->edit)  && $getActionName != 'index') {
                    $decodedPermissions['edit.'.$getSectionName] = 1;
                } elseif (in_array($getActionName, $staticVar->delete)  && $getActionName != 'index') {
                    $decodedPermissions['delete.'.$getSectionName] = 1;
                }
            }
        }
        return $decodedPermissions;
    }
}
