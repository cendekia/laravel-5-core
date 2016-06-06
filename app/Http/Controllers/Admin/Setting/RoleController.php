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
            'id', 'name', 'whitelisted_ip_addresses', 'created_at', 'updated_at'
        ];

        $this->editableFields = [
            // 'parent_role_id' => 'text|required',
            'name' => 'text|required',
            // 'permissions' => 'text|required',
            // 'whitelisted_ip_addresses' => 'text|required',
        ];
    }

    public function getData()
    {
        $query = $this->model->select($this->columns);

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
        $formAttr = [
            'url' => $this->url,
            'view' => 'setting_form',
            'method' => 'post',
            'fields' => $this->editableFields,
            'currentPage' => 'add new role'
        ];

        return parent::getForm(null, $formAttr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $query = new Role;

        foreach ($this->editableFields as $field => $value) {
            $query->{$field} = $request->{$field};
        }

        $query->slug = str_slug($request->name);

        if (!$query->save())
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
        $query = $this->model->find($id);

        $formAttr = [
            'url' => $this->url . '/' . $id,
            'view' => 'setting_form',
            'method' => 'put',
            'fields' => $this->editableFields,
            'currentPage' => 'Edit Role: '. $query->name
        ];

        return parent::getForm($query, $formAttr);
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

        foreach ($this->editableFields as $field => $value) {
            $query->{$field} = $request->{$field};
        }

        $query->slug = str_slug($request->name);

        if (!$query->save())
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
