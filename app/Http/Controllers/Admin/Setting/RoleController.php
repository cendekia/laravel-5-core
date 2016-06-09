<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(Role $model)
    {
        parent::__construct();

        $this->model = $model;
        $this->url = url('admin/setting/roles');

        $this->columns = [
            'id', 'name'
        ];

        $this->editableFields = [
            'parent_role_id' => 'select|null|parent_role',
            'name' => 'text|required',
            'whitelisted_ip_addresses' => 'text',
            'permissions' => 'text',
        ];

        $this->role = $this->admin->roles()->first();
    }

    public function getData()
    {
        $query = $this->model->select($this->columns);

        if ($this->admin->id != 1) {
            $query = $query->where('parent_role_id', '=', $this->role->id);
        }

        return \Datatables::of($query)
            ->addColumn('action', function ($query) {
                $actionUrl = $this->url.'/'.$query->id;

                return \Admin::editButton($actionUrl.'/edit').\Admin::deleteButton($actionUrl);
            })
            ->removeColumn('id')
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = $this->columns;
        unset($columns[0]);

        return parent::getTable($columns, $this->url, 'setting_table');

        // return view('admin.setting.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleLists = $this->model;

        if ($this->admin->id != 1) {
            $roleLists = $roleLists->where('parent_role_id', '=', $this->role->id);
        }

        $roleLists = $roleLists->lists('name', 'id')->all();

        $currentIp = (object) ['whitelisted_ip_addresses' => \Request::ip()];

        $formAttr = [
            'query' => $currentIp,
            'url' => $this->url,
            'view' => 'setting_form',
            'method' => 'post',
            'fields' => $this->editableFields,
            'pageTitle' => 'add new role'
        ];

        return view('admin.setting.roles.form', compact('formAttr', 'roleLists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->name) {
            $slug = str_slug($request->name);
            $request->merge(['slug' => $slug]);
        }

        if (!Role::saveThese($request))
            return redirect()->back()->withErrors('Ouch! Add admin failed.');

        return redirect($this->url)->with('status', 'A new role has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roleLists = $this->model;

        if ($this->admin->id != 1) {
            $roleLists = $roleLists->where('parent_role_id', '=', $this->role->id);
        }

        $roleLists = $roleLists->lists('name', 'id')->all();

        $query = $this->model->find($id);

        $query->permissions = Role::permissionDecodes($query->permissions);

        $formAttr = [
            'query' => $query,
            'url' => $this->url . '/' . $id,
            'view' => 'setting_form',
            'method' => 'put',
            'fields' => $this->editableFields,
            'pageTitle' => 'Edit Role: '. $query->name
        ];

        return view('admin.setting.roles.form', compact('formAttr', 'roleLists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->name) {
            $slug = str_slug($request->name);
            $request->merge(['slug' => $slug]);
        }

        if ($request->parent_role_id) {
            $targetRole = Role::find($request->parent_role_id);

            if(isset($targetRole) && $targetRole->parent_role_id == $id)
                return redirect()->back()->withErrors('Ouch! Can\'t assign its child become parent role.');
        }

        if (!Role::saveThese($request, $id))
            return redirect()->back()->withErrors('Ouch! Update failed.');

        return redirect($this->url)->with('status', ucwords($this->page) . ' data updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == 1) {
            return redirect()->back()->withErrors('Ouch! You can\'t delete the <strong>alpha</strong> role.');
        }

        $query = $this->model->find($id);

        if (!$query->delete())
            return redirect()->back()->withErrors('Ouch! Delete failed.');

        return redirect()->back()->with('status', 'Data has been deleted!');
    }
}
