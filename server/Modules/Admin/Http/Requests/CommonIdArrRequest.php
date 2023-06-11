<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonIdArrRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }
	public function rules()
    {
        return [
			'idArr' => 'required|is_array_integer',
        ];
    }
	public function messages(){
		return [
			'idArr.required' 				=> '缺少参数（idArr）！',
			'idArr.is_array_integer' 	=> '（idArr）参数错误！',
		];
	}
}









