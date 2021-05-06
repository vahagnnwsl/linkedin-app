<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'avatar' => 'mimes:jpg,bmp,png|max:2048',
            'color' => 'required|string|max:255',
//            'linkedin_login' => 'sometimes|string|max:255',
//            'linkedin_password' => 'sometimes|string|max:255',
        ];


    }
}
