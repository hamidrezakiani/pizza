<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class MobileVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'mobile' => 'required|regex:/(0)[0-9]{10}/',
            'code'   => 'required|regex:/^[0-9]{4}$/'
        ];
    }

    public function attributes()
    {
        return [
            'mobile' => 'شماره موبایل',
            'code' => 'کد تایید',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute ضروری میباشد.',
            'regex' => 'فرمت :attribute معتبر نیست',
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
