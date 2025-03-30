<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Rules\FirstLetterUppercaseRule;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login'      => [
                'required',
                'string',
                'min:4',
                'max:255',
                Rule::unique('users', 'login')
            ],
            'password'   => [
                'required',
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
            'first_name' => [
                'required',
                'string',
                'max:255',
                new FirstLetterUppercaseRule(),
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
                new FirstLetterUppercaseRule(),
            ],
            'email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
        ];
    }
}
