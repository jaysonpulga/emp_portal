<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\UserProfileRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    //
    public function index() {

        $employee = User::where('email', '=', Auth::user()->email)->first();

        $companies = Company::orderBy('name')->get();

        return view('user.profile', compact('employee', 'companies'));
    }
    public function store(UserProfileRequest $request) {
        $data = User::where('email', '=',  Auth::user()->email)->first();
        $data->address = $request->address;
        $data->mobile = $request->mobile;
        $data->company_id = $request->company_id;
        $data->designation = $request->designation;
        $data->save();

        return redirect()->back()->with('message', 'Record successfully updated.');
    }
}
