<?php

namespace App\Http\Requests;

use App\Lib\ResponseTemplate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    use ResponseTemplate;
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
            'mobile' => 'required|regex:/(0)[0-9]{10}/|unique:users,mobile',
            'password'   => 'required|string|min:8',
        ];
    }

    public function attributes()
    {
        return [
            'mobile' => 'شماره موبایل',
            'password' => 'کلمه عبور',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute ضروری میباشد.',
            'regex' => 'فرمت :attribute معتبر نیست',
            'min' => [
                'string' => ':attribute باید بیشتر از :min کاراکتر باشد',
            ],
            'unique' => 'این :attribute قبلا استفاده شده',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->setStatus(422);
        $this->setErrors($validator->errors());
        throw new ValidationException($validator, $this->response());
    }
}
