<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('.شما مجوز دسترسی به این عمل را ندارید');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'integer|exists:categories,id',
            'name' => 'string|max:99',
            'active' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'parent_id'  => 'سر دسته',
            'name'       => 'نام دسته',
            'active'     => 'وضعیت(فعال/غیر فعال)',
        ];
    }

    public function messages()
    {
        return [
            'integer' => 'مقدار :attribute باید عددی باشد',
            'exists' => ':attribute انتخاب شده معتبر نیست',
            'string' => ':attribute باید به صورت رشته باشد',
            'min' => [
                'string' => ':attribute نباید کم تر از :min کاراکتر باشد',
            ],
            'boolean' => ':attribute باید 0 یا 1 باشد',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'data' => [],
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
