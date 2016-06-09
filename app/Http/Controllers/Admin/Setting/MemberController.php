<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(User $model)
    {
        parent::__construct();

        $this->model = $model;
        $this->url = url('admin/setting/members');

        $this->columns = [
            'users.id', 'users.name', 'users.email', 'roles.name'
        ];

        //fieldType|required|label|data
        $this->editableFields = [
            'name' => 'text|required',
            'role' => 'select|required|role|roleLists',
            'email' => 'email|required',
            'password' => 'password|required'
        ];

        $this->role = $this->admin->roles()->first();
    }

    public function getData()
    {
        $admin = $this->admin;
        $columns = $this->columns;
        $query = $this->model->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email', 'roles.name as roles');

        if ($this->admin->id != 1) {
            $allowedRoles = Role::where('parent_role_id', '=', $this->role->id)->lists('id')->all();

            $query = $query->whereIn('roles.id', $allowedRoles);
        }

        return \Datatables::of($query)
            ->addColumn('action', function ($query) use ($admin) {
                $actionUrl = $this->url.'/'.$query->id;

                return \Admin::editButton($actionUrl.'/edit', $admin).\Admin::deleteButton($actionUrl, $admin);
            })->make(true);
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

        return parent::getTable($columns, $this->url, 'setting_table', null, 'users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleLists = new Role;

        if ($this->admin->id != 1) {
            $roleLists = $roleLists->where('parent_role_id', '=', $this->role->id);
        }

        $roleLists = $roleLists->lists('name', 'id')->all();

        $data = [
            'roleLists' => $roleLists
        ];

        $formAttr = [
            'url' => $this->url,
            'view' => 'setting_form',
            'method' => 'post',
            'fields' => $this->editableFields,
            'pageTitle' => 'add new member'
        ];

        return parent::getForm(null, $formAttr, $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $query = new User;
        $query->name = $request->name;
        $query->email = $request->email;
        $query->password = \Hash::make($request->password);
        $query->restricted_access = 1;

        if (!$query->save()) {
            return redirect()->back()->withErrors('Ouch! Add admin failed.');
        } else {
            $query->roles()->attach($request->role);
        }

        return redirect($this->url)->with('status', $query->email .' has been added!');
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
        $query = $this->model->find($id);
        $role = $query->roles()->first();
        $query->role = ($role) ? $role->id : null;

        $this->editableFields['password'] = 'password';

        $formAttr = [
            'url' => $this->url . '/' . $id,
            'view' => 'setting_form',
            'method' => 'put',
            'fields' => $this->editableFields,
            'pageTitle' => 'Edit Member: '. $query->name
        ];

        $roleLists = new Role;

        if ($this->admin->id != 1) {
            $roleLists = $roleLists->where('parent_role_id', '=', $this->role->id);
        }

        $roleLists = $roleLists->lists('name', 'id')->all();

        $data = [
            'roleLists' => $roleLists
        ];

        return parent::getForm($query, $formAttr, $data);
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
        $query = $this->model->find($id);
        $query->name = $request->name;
        $query->email = $request->email;

        if (!empty($request->password)) {
            $query->password = \Hash::make($request->password);
        }

        if (!$query->save()) {
            return redirect()->back()->withErrors('Ouch! Update failed.');
        } else {
            if (count($query->roles())) {
                $query->roles()->detach();
            }

            $query->roles()->attach($request->role);
        }

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
            return redirect()->back()->withErrors('Ouch! You can\'t delete the <strong>alpha</strong> user.');
        } elseif ($id == $this->admin->id) {
            return redirect()->back()->withErrors('Ouch! You can\'t delete yourself.');
        }

        $query = $this->model->find($id);
        $role = $query->roles()->first();

        if (!$query->delete()) {
            return redirect()->back()->withErrors('Ouch! Delete failed.');
        } elseif($role) {
            $query->roles()->detach($role->id);
        }

        return redirect()->back()->with('status', $query->email . ' has been deleted!');
    }
}
