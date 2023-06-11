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
 * @Name   自定义状态码
 * @Description
 * @Auther hengcool
 * @Date 2021/6/4 19:40
 */

namespace Modules\Common\Exceptions;


class StatusData
{
    const BAD_REQUEST = 40000;
    const INTERNAL_SERVER_ERROR = 50000;
    const Ok = 20000;

    const PARES_ERROR = 50001;
    const REFLECTION_EXCEPTION = 50002;
    const RUNTIME_EXCEPTION = 50003;
    const ERROR_EXCEPTION = 50004;
    const Error = 50005;
    const BAD_METHOD_CALL_EXCEPTION = 50006;

    const INVALID_ARGUMENT_EXCEPTION = 60000;
    const MODEL_NOT_FOUND_EXCEPTION = 60001;
    const QUERY_EXCEPTION = 60002;

    const TOKEN_ERROR_KEY = 70001;
    const TOKEN_ERROR_SET = 70002;
    const TOKEN_ERROR_BLACK = 70003;
    const TOKEN_ERROR_EXPIRED = 70004;
    const TOKEN_ERROR_JWT = 70005;
    const TOKEN_ERROR_JTB = 70006;
}
