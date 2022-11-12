<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class StoreRoleRequest extends FormRequest
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
            'name' => 'required|unique:roles',
        ];
    }

    public function messages()
    {
       return [
           'required' => ':attribute ضرووری است.',
           'unique' => ':attribute تکراری است.'
       ];
    }

    public function attributes()
    {
       return [
           'name' => 'نام نقش',
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
