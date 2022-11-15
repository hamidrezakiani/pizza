<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseRequest
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
}
