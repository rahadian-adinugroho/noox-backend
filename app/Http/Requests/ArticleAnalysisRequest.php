<?php

namespace Noox\Http\Requests;

use Dingo\Api\Http\FormRequest;

class ArticleAnalysisRequest extends FormRequest
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
            'src'     => 'required_without:article|url',
            'title'   => 'nullable',
            'article' => 'required_without:src',
        ];
    }
}
