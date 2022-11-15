<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;

class UpdateCategoryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create-category');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'integer|exists:categories,id',
            'name' => 'string|max:99',
            'active' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'parent_id'  => 'سر دسته',
            'name'       => 'نام دسته',
            'active'     => 'وضعیت(فعال/غیر فعال)',
        ];
    }
}
