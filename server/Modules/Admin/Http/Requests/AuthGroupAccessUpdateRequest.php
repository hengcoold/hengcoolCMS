<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthGroupAccessUpdateRequest extends FormRequest
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
			'id' => 'required|is_positive_integer',
            'rules' => 'required'
        ];
    }
	public function messages(){
		return [
			'id.required' 				=> '缺少参数（id）！',
			'id.is_positive_integer' 	=> '（id）参数错误！',
			'rules.required' 			=> '请选择配置项！'
		];
	}
}









