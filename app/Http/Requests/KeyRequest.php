<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeyRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'country_id'=>'required|exists:App\Models\Country,id',
            'accounts_id'=>'required|array|min:1',
            'status'=>'sometimes'
        ];
    }
}
