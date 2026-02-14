<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AbstractUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        #todo return $this->user->hasRole('admin');

        return true;
    }

    public function rules(): array
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'failed' => true,
                'message' => 'Ошибка валидации',
                'errors'  => $validator->errors()
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'name.max'       => 'Имя должно содержать менее :max символов.',
            'email.max'      => 'Email должен содержать менее :max символов.',
            'email.unique'   => 'Такой email уже используется.',
            'roles.*.exists' => 'Одна или несколько ролей не существует.'
        ];
    }
}
