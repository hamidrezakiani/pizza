<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UserRolesRequest extends BaseRequest
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
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'کاربر',
        ];
    }
}
