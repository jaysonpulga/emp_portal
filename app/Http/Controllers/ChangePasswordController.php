<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    //
    public function index() {

        $employee = User::where('email', '=', Auth::user()->email)->first();

        return view('user.changepassword', compact('employee'));
    }
    public function update(ChangePasswordRequest $request) {


        $data = User::where('email', '=',  Auth::user()->email)->first();
        $data->password = bcrypt($request->password);
        $data->save();

        return redirect()->back()->with('message', 'Password successfully updated.');
    }
}
