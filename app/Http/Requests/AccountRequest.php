<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
        $rules = [
            'full_name' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:accounts,login',
            'entityUrn' => 'required|string|max:255|unique:accounts,entityUrn'

        ];

        if ($this->method() === 'PUT') {
            $rules['login'] .= ',' . $this->route('id');
            $rules['entityUrn'] .= ',' . $this->route('id');
        }

        return $rules;


    }
}
