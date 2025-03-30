<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        Log::info('req   ');
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
                'max:1000',
            ],
            'status'          => [
                'required',
                'string',
                Rule::in(Task::STATUSES),
            ],
            'start_date_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
        ];
    }
}
