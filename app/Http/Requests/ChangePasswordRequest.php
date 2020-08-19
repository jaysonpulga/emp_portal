<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'password' => 'required|confirmed'
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $user = User::where('email', '=', Auth::user()->email)->first();

            if (Hash::check(Request::input('old_password'), $user->password)) {
            }
            else {
                $validator->errors()->add('old_password', 'Incorrect old password.');
            }
        });
    }
}
