<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetUserPasswordRequest;
use App\User;

class ResetUserPasswordController extends Controller
{
    //
    public function index() {
        return view('admin.resetuserpassword');
    }
    public function reset(ResetUserPasswordRequest $request) {

        $data = User::where('email', '=',  $request->email)->first();
        $data->password = bcrypt($request->password);
        $data->save();

        return redirect()->back()->with('message', 'Password successfully changed.');
    }
}
