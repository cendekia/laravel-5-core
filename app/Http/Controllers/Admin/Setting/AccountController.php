<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests;
use App\Models\AdminProfile;
use App\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->account = ($this->admin) ? User::whereId($this->admin->id)->with('adminProfile')->first() : null;
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
        $status = 'Profile updated!';
        $account = $this->account;
        $account->name = $request->name;

        if ($request->current_password != '') {
            if (!\Hash::check($request->current_password, $account->password)) {
                return redirect()->back()->withErrors("Your current password doesn't match.");
            } elseif ($request->password && $request->password != $request->password_confirmation) {
                return redirect()->back()->withErrors("Your new password doesn't match with password confirmation.");
            }

            $account->password = \Hash::make($request->password);
            $status = 'Your password has been updated.';
        }

        if ($request->hasFile('profile_picture')) {
            $folderPath = public_path('contents/profile_pictures');
            if (!\File::isDirectory($folderPath)) \File::makeDirectory($folderPath, 0775, true);

            $fileName = $request->profile_picture->getClientOriginalName();
            $newName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . strtotime('now') .'.'. pathinfo($fileName, PATHINFO_EXTENSION);

            \Image::make($request->profile_picture)->fit(90, 90)->save($folderPath . '/' . $newName);
        }

        if (!$account->save())
            return redirect()->back()->withErrors('Update failed!');

        if ($request->hasFile('profile_picture')) {
            $checkExisting = AdminProfile::whereUserId($account->id)->first();

            if ($checkExisting) {
                $oldProfilePicture = $checkExisting->profile_picture;
            }

            $addProfile = ($checkExisting) ?: new AdminProfile;
            $addProfile->profile_picture = $newName;
            $addProfile->user_id = $account->id;

            if ($addProfile->save() && $checkExisting) \File::delete(public_path('contents/profile_pictures') . '/' . $oldProfilePicture);
        }

        return redirect()->back()->with('status', $status);
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
