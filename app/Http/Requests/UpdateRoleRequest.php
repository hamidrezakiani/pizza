<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UpdateRoleRequest extends BaseRequest
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

    public function attributes()
    {
        return [
            'name' => 'نام نقش',
        ];
    }
}
