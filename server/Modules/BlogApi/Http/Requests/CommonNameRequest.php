<?php

namespace Modules\BlogApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonNameRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }
	public function rules()
    {
        return [
			'name' => 'required',
        ];
    }
	public function messages(){
		return [
			'name.required' 				=> '缺少参数（name）！'
		];
	}
}









