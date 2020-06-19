<?php

namespace Noox\Http\Requests;

use Dingo\Api\Http\FormRequest;

class AddFcmTokenRequest extends FormRequest
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
            'fcm_token' => 'required|min:151'
        ];
    }
}
