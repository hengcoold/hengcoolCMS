<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
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
			'group_id' => 'required|is_positive_integer',
            'username' => 'required|unique:auth_admins|regex:/^[a-zA-Z0-9]{4,14}$/',
            'password'  => 'required|confirmed|regex:/^[a-zA-Z0-9]{4,14}$/'

        ];
    }
	public function messages(){
		return [
			'group_id.required' => '请选择权限组！',
			'group_id.is_positive_integer' => '请选择权限组！',
			'username.required' => '请输入账号！',
			'username.unique' => '账号已存在！',
			'username.regex' => '账号必须4到14位的数字或字母！',
			'password.required' => '请输入密码！',
			'password.confirmed' => '两次密码输入不一致！',
			'password.regex' => '密码必须4到14位的数字或字母！',

		];
	}
}









