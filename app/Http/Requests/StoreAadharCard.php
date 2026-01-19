<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAadharCard extends FormRequest
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
            'aadhar_file' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'pan_file' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ];
    }
}
