<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class RoleDetachActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('roles');
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
            'role_id' => 'required|exists:roles,id',
            'action_id' => 'required|exists:actions,id',
        ];
    }

    public function attributes()
    {
        return [
            'role_id' => 'نقش',
            'action_id' => 'عملیات',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'انتخاب :attribute ضروری است.',
            'exists' => ':attribute انتخاب شده وجود ندارد.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'data' => [],
            'errors' =>  $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
