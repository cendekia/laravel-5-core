<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use App\Model\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function __construct(Subscribe $model)
    {
        parent::__construct();

        $this->model = $model;
        $this->url = url('admin/subscribes');

        $this->columns = [
            'id', 'email', 'created_at'
        ];

        //fieldType|required|label|data
        $this->editableFields = [
            'email' => 'email|required',
            'active' => 'select|required|status|subscribeStatus',
        ];
    }

    public function getData()
    {
        $admin = $this->admin;
        $query = $this->model->select($this->columns);

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

        return parent::getTable($columns, $this->url);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'subscribeStatus' => [0 => 'Inactive', 1 => 'Active']
        ];

        $formAttr = [
            'url' => $this->url,
            'method' => 'post',
            'fields' => $this->editableFields,
            'pageTitle' => 'add new subscribe'
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
        $query = new Subscribe;

        foreach ($this->editableFields as $key => $value) {
             $query->{$key} = $request->{$key};
        }

        if (!$query->save()) {
            return redirect()->back()->withErrors('Ouch! Add subscriber failed.');
        }

        return redirect($this->url)->with('status', 'Data has been added!');
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
            'method' => 'put',
            'fields' => $this->editableFields,
            'pageTitle' => 'edit subscriber'
        ];

        $data = [
            'subscribeStatus' => [0 => 'Inactive', 1 => 'Active']
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

        foreach ($this->editableFields as $key => $value) {
             $query->{$key} = $request->{$key};
        }

        if (!$query->save()) {
            return redirect()->back()->withErrors('Ouch! Update failed.');
        }

        return redirect($this->url)->with('status', 'Data updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = $this->model->find($id);

        if (!$query->delete()) {
            return redirect()->back()->withErrors('Ouch! Delete failed.');
        }

        return redirect()->back()->with('status', $query->email . ' has been deleted!');
    }
}
