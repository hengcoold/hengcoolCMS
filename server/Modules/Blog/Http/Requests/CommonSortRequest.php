<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonSortRequest extends FormRequest
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
			'sort' => 'required|regex:/^[1-9]{1}[0-9]{0,10}$/',

        ];
    }
	public function messages(){
		return [
			'id.required' 				=> '缺少参数（id）！',
			'id.is_positive_integer' 	=> '（id）参数错误！',
			'sort.required' => '排序必须为整数！',
			'sort.regex' => '排序必须为整数！',
		];
	}
}









