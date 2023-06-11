<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthGroupStoreRequest extends FormRequest
{
    /**
     * php artisan module:make-request AdminRequest Admin
     */

    public function authorize()
    {
        return true;
    }
	public function rules()
    {
        return [
            'name' => 'required|unique:auth_groups|min:2|max:20'
        ];
    }
	public function messages(){
		return [
			'name.required' => '请输入权限组名称！',
			'name.unique' => '权限组名称已存在！',
			'name.min' => '权限组名称必须2到20位！',
			'name.max' => '权限组名称必须2到20位！',
		];
	}
}









