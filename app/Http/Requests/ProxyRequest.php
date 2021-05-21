<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProxyRequest extends FormRequest
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
            'login' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'ip' => 'required|string|max:255',
            'port' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'type' => 'required|string|max:255'
        ];

    }
}
