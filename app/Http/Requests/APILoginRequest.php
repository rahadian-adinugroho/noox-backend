<?php

namespace Noox\Http\Requests;

use Dingo\Api\Http\FormRequest;

class APILoginRequest extends FormRequest
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
            'email' => 'nullable|email',
            'password' => 'nullable|min:6',
            'fb_token' => 'nullable',
            'fcm_token' => 'required|min:151',
        ];
    }
}
