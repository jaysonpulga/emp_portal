<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadDmtRequest extends FormRequest
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
            'work_date' => 'required|date|date_format:Y-m-d',
        ];
    }
    public function messages()
    {
        return [
            'work_date.required' => 'Work date is required.',
            'work_date.date_format' => 'Invalid date format.',
        ];
    }
}
