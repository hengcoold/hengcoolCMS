<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }
	public function rules()
    {
        return [
			'table' => 'required',
        ];
    }
	public function messages(){
		return [
			'table.required' 				=> '缺少参数（table）！'
		];
	}
}









