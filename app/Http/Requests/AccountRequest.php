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
            'proxy_id'=>'sometimes|exists:App\Models\Proxy,id',
            'cookie_web_str'=>'sometimes',
            'cookie_socket_str'=>'sometimes',
            'status'=>'sometimes',
            'type'=>'required',
//            'limit_conversation'=>'required|integer|min:1|max:150',
            'limit_connection_request'=>'sometimes|integer|min:1|max:150',

        ];

        if ($this->method() === 'PUT') {
            $rules['login'] .= ',' . $this->route('id');
            $rules['entityUrn'] = 'required|string|max:255|unique:accounts,entityUrn,' . $this->route('id');
        }

        return $rules;


    }
}
