<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Rules\FirstLetterUppercaseRule;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login'       => [
                'sometimes',
                'string',
                'min:4',
                'max:255',
                Rule::unique('users', 'login')->ignore($this->route('id')),
            ],
            'password'    => [
                'sometimes',
                'string',
                'min:6',
                Password::min(8)
                    ->letters()
                    ->numbers(),
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!Str::contains($value, ['_', '-', '.', ','])) {
                        $fail("The {$attribute} must contain at least one of the following symbols: _ - , . ");
                    }
                },
            ],
            'first_name'  => [
                'sometimes',
                'string',
                'max:255',
                new FirstLetterUppercaseRule(),
            ],
            'last_name'   => [
                'sometimes',
                'string',
                'max:255',
                new FirstLetterUppercaseRule(),
            ],
            'email'       => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('id')),
            ],
        ];
    }
}
