<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseListRequest;
use Illuminate\Validation\Rule;

class GetListTaskRequest extends BaseListRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['order_by'] = [
            'nullable',
            'string',
            Rule::in(['title', 'status']),
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            ...parent::messages(),
            'order_by.in' => 'The order_by field must be one of: title, status.',
        ];
    }
}
