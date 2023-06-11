<?php
// +----------------------------------------------------------------------
// | Name: hengcool管理系统 [ 为了快速搭建软件应用而生的，希望能够帮助到大家提高开发效率。 ]
// +----------------------------------------------------------------------
// | Copyright: (c) 2020~2021 https://www.lvacms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed: 这是一个自由软件，允许对程序代码进行修改，但希望您留下原有的注释。
// +----------------------------------------------------------------------
// | Author: hengcool <260894671@qq.com>
// +----------------------------------------------------------------------
// | Version: V1
// +----------------------------------------------------------------------

/**
 * @Name
 * @Description
 * @Auther hengcool
 * @Date 2021/6/12 02:48
 */

namespace Modules\BlogApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class CommonPageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'page' => 'required|is_positive_integer',
            'limit' => 'required|is_positive_integer',
        ];
    }
    public function messages(){
        return [
            'page.required' 				=> '缺少参数（page）！',
            'page.is_positive_integer' 	=> '（page）参数错误！',
            'limit.required' 				=> '缺少参数（limit）！',
            'limit.is_positive_integer' 	=> '（limit）参数错误！',
        ];
    }
}
