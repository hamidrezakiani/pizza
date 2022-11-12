<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;

class ShowRoleRequest extends FormRequest
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

    // public function all($keys = null)
    // {
    //      $request = Request::all();
    //     $request['role'] = $this->route('role');
    //     return $request;
    // }
    public function rules()
    {
        return [
            //
        ];
    }
}
