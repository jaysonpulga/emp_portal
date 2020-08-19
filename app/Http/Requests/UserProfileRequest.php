<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'address' => 'required|max:80',
            'mobile' => 'required|max:80',
            'company_id' => 'required',
            'designation' => 'required|max:80',
        ];
    }
    public function messages() {
        return [
            'address.required' => 'Address is required.'
        ];
    }
}
