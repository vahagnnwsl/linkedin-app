<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'keys_ides' => 'sometimes|array',
            'email' => 'required|string|max:255|email|unique:users,email',
        ];


        if ($this->method() === 'POST') {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['email'] .= ',' . $this->route('id');
            $rules['role_id'] = 'required|exists:Spatie\Permission\Models\Role,id';
            $rules['account_id'] = 'sometimes|exists:App\Models\Account,id';
            $rules['status'] = 'required';

        }

        return $rules;

    }
}
