<?php

namespace App\Http\Requests;

use App\Lib\ResponseTemplate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    use ResponseTemplate;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function failedAuthorization()
    {
        $this->setErrors(['message' => '.شما مجوز دسترسی به این عمل را ندارید']);
        $this->setStatus(403);
        throw new ValidationException(null, $this->response());
    }

    public function messages()
    {
        return [
            'required' => ':attribute ضروری میباشد.',
            'regex' => 'فرمت :attribute معتبر نیست',
            'integer' => 'مقدار :attribute باید عددی باشد',
            'exists' => ':attribute انتخاب شده معتبر نیست',
            'string' => ':attribute باید به صورت رشته باشد',
            'min' => [
                'string' => ':attribute نباید کم تر از :min کاراکتر باشد',
            ],
            'max' => [
                'string' => ':attribute نمیتواند بیش تر از :min کاراکتر باشد',
            ],
            'boolean' => ':attribute باید 0 یا 1 باشد',
            'unique' => 'این :attribute قبلا استفاده شده',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->setStatus(422);
        $this->setErrors($validator->errors()->messages());
        throw new ValidationException($validator, $this->response());
    }

    // public function all($keys = null)
    // {
    //      $request = Request::all();
    //     $request['role'] = $this->route('role');
    //     return $request;
    // }
}
