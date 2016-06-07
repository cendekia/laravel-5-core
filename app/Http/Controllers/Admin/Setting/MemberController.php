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
            'id', 'name', 'email', 'created_at'
        ];

        // fieldType|required|label|data
        $this->editableFields = [
            'name' => 'text|required',
            'role' => 'select|required|role|roleLists',
            'email' => 'email|required',
            'password' => 'password|required'
        ];
    }

    public function getData()
    {
        $query = $this->model->select($this->columns);

        return \Datatables::of($query)
            ->addColumn('action', function ($query) {
                $actionUrl = $this->url.'/'.$query->id;

                return \Admin::editButton($actionUrl.'/edit').\Admin::deleteButton($actionUrl);
            })->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response

    public function index()
    {
        return parent::getTable($this->columns, $this->url, 'setting_table');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'roleLists' => Role::lists('name', 'id')->all()
        ];

        $formAttr = [
            'url' => $this->url,
            'view' => 'setting_form',
            'method' => 'post',
            'fields' => $this->editableFields,
            'currentPage' => 'add new member'
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

        if (!$query->save())
            return redirect()->back()->withErrors('Ouch! Add admin failed.');

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

        $formAttr = [
            'url' => $this->url . '/' . $id,
            'view' => 'setting_form',
            'method' => 'put',
            'fields' => $this->editableFields,
            'currentPage' => 'Edit Member: '. $query->name
        ];

        $data = [
            'roleLists' => Role::lists('name', 'id')->all()
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
        $query->password = \Hash::make($request->password);

        if (!$query->save()) {
            return redirect()->back()->withErrors('Ouch! Update failed.');
        } else {
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
