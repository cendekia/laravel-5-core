<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->account = User::whereId($this->admin->id)->with('adminProfile')->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = $this->account;

        return view('admin.setting.account.index', compact('account'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = $this->account;
        $account->name = $request->name;

        if ($request->current_password != '') {
            if (!\Hash::check($request->current_password, $account->password)) {
                return redirect()->back()->withErrors("Your current password doesn't match.");
            } elseif ($request->password && $request->password != $request->password_confirmation) {
                return redirect()->back()->withErrors("Your new password doesn't match with password confirmation.");
            }

            $account->password = \Hash::make($request->password);

            if ($account->save()) {
                return redirect()->back()->with('status', 'Your password has been updated.');
            }
        }

        return redirect()->back()->with('status', 'Profile updated!');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
