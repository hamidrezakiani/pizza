<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends BaseRequest
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
}
