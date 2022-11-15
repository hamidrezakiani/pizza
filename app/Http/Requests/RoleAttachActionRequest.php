<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class RoleAttachActionRequest extends BaseRequest
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
}
