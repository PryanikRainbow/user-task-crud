<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseRequest;
use App\Models\Task;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'           => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'description'     => [
                'required',
                'string',
                'min:1',
            ],
            'status'          => [
                'sometimes',
                'string',
                Rule::in(Task::STATUSES)
            ],
            'start_date_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            ...parent::messages(),
            'status.in' => "The status field must be one of: " . implode(', ', Task::STATUSES) . ".",
        ];
    }
}
