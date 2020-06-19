<?php

namespace Noox\Http\Requests;

use Dingo\Api\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'name'     => 'required|regex:/^[A-Za-z\s]+$/u',
            'gender'   => 'nullable|size:1',
            'birthday' => 'nullable|date',
            'fb_token' => 'nullable|alpha_num',
            'fcm_token' => 'required|min:151'
        ];
    }
}
