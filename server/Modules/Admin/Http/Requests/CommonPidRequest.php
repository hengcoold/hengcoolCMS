<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonPidRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }
	public function rules()
    {
        return [
			'pid' => 'required|is_pid_integer',
        ];
    }
	public function messages(){
		return [
			'pid.required' 				=> '缺少参数（pid）！',
			'pid.is_pid_integer' 	=> '（pid）参数错误！',
		];
	}
}









