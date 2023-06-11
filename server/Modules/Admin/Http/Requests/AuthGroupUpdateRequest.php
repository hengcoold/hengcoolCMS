<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthGroupUpdateRequest extends FormRequest
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
            'name' => 'required|min:2|max:20|unique:auth_groups,name,'.$this->id
        ];
    }
	public function messages(){
		return [
			'id.required' 				=> '缺少参数（id）！',
			'id.is_positive_integer' 	=> '（id）参数错误！',
			'name.required' 			=> '请输入权限组名称！',
			'name.unique' 				=> '权限组名称已存在！',
			'name.min' 					=> '权限组名称必须2到20位！',
			'name.max' 					=> '权限组名称必须2到20位！',
		];
	}
}









