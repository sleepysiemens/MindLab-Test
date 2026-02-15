<?php

namespace App\Http\Requests\Roles;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AbstractRoleRequest extends FormRequest
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
            'name.max'       => 'Название должно содержать менее :max символов.',
            'name.required'  => 'Название должно быть заполнено.',
            'guard_name.max' => 'Guard name должен содержать менее :max символов.',
        ];
    }
}
