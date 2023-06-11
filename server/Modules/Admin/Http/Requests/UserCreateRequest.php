<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name'=> 'required',
            'phone'=>'required|unique:auth_users|regex:/^1[34578]{1}\d{9}$/',
            'email'=>'required|unique:auth_users|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/',
            'password'=>'required|confirmed|regex:/^[a-zA-Z0-9]{4,14}$/',
            'province_id'=> 'required|is_positive_integer',
            'city_id'=> 'required|is_positive_integer',
            'county_id'=> 'required|is_positive_integer',
            'birth'=> 'required'
        ];
    }
	public function messages(){
		return [
		    'name.required'=>'请输入姓名！',
		    'phone.required'=>'请输入手机号！',
		    'phone.unique'=>'手机号已注册！',
		    'phone.regex'=>'请输入正确的手机号！',
            'email.required'=>'请输入邮箱！',
            'email.unique'=>'邮箱已注册！',
            'email.regex'=>'请输入正确的邮箱！',
            'password.required' => '请输入密码！',
            'password.confirmed' => '两次密码输入不一致！',
            'password.regex' => '密码必须4到14位的数字或字母！',
			'province_id.required' => '请选择地址！',
			'city_id.required' => '请选择地址！',
			'county_id.required' => '请选择地址！',
            'province_id.is_positive_integer' => '请选择地址！',
            'city_id.is_positive_integer' => '请选择地址！',
            'county_id.is_positive_integer' => '请选择地址！',
			'birth.required' => '请选择出生年月日！'
		];
	}
}









