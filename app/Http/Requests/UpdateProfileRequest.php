<?php

namespace Noox\Http\Requests;

use Dingo\Api\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'birthday' => 'required|date',
            'name'     => 'required|regex:/^[A-Za-z\s]+$/u',
            'gender'   => 'nullable|size:1',
        ];
    }
}
