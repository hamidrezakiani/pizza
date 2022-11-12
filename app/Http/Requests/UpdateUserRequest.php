<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update-user');
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
            'firstName' => 'string|max:30',
            'lastName'  => 'string|max:30'
        ];
    }

    public function attributes()
    {
        return [
            'firstName' => 'نام',
            'lastName' => 'نام خانوادگی',
        ];
    }

    public function messages()
    {
        return [
            'string' => ':attribute باید به صورت رشته باشد',
            'max' => [
                'string' => ':attribute نمیتواند بیش تر از :min کاراکتر باشد',
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'data' => [],
            'errors' =>  $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
