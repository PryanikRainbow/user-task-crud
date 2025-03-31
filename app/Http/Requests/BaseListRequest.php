<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class BaseListRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page'      => ['int', 'min:1'],
            'per_page'  => ['int', 'min:1'],
            'order_by'  => ['string'],
            'order_dir' => ['string', Rule::in(['asc', 'desc'])],
        ];
    }

    public function messages(): array
    {
        return [
            'order_dir.in' => 'The order_by field must be one of: asc, desc.',
        ];
    }
}
