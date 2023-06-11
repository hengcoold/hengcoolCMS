<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthOpenRequest extends FormRequest
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
			'id' 		=> 'required|is_positive_integer',
			'auth_open' 	=> 'required|is_status_integer',

        ];
    }
	public function messages(){
		return [
			'id.required' 					=> '缺少参数（id）！',
			'id.is_positive_integer' 		=> '（id）参数错误！',
			'auth_open.required' 				=> '缺少参数（status）！',
			'auth_open.is_status_integer' 		=> '（status）参数错误！',
		];
	}
}









