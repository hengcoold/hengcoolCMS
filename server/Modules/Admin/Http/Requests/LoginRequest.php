<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	/**
     * php artisan module:make-request LoginRequest AuthAdmin
     */


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
			'username' => 'required',
            'password'  => 'required'
        ];
    }
	public function messages(){
		return [
			'username.required' => '请输入账号！',
			'password.required' => '请输入密码！',
		];
	}

}
