<?php

namespace App\Http\Requests;


class MobileVerificationRequest extends BaseRequest
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
}
