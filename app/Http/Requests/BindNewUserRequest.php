<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BindNewUserRequest extends Request
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
            'phone'       => 'required|min:9|max:15|unique:users',
            'password'    => 'required|min:6|max:24|confirmed',
            'code'       => 'required'
        ];
    }
}
