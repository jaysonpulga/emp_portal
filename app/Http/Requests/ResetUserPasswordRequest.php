<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Support\Facades\Request;

class ResetUserPasswordRequest extends FormRequest
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
            'email' => 'required',
            'password' => 'required|confirmed|min:6',

        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least six (6) characters',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::where('email', Request::input('email'))->count();
            if ($user == 0) {
                $validator->errors()->add('email', 'Email address not found!');
            }
        });
    }
}
