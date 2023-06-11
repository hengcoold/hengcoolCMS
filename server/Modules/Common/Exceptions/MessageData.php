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
 * @Name    提示信息数据
 * @Description
 * @Auther hengcool
 * @Date 2021/6/4 19:43
 */

namespace Modules\Common\Exceptions;


class MessageData
{
    const BAD_REQUEST = '服务端异常！';
    const INTERNAL_SERVER_ERROR = '服务器错误！';
    const Ok = '操作成功！';
    const PARES_ERROR = '服务器语法错误！';
    const Error = '服务器语法错误，请注意查看信息！';
    const REFLECTION_EXCEPTION = '服务器异常映射！';
    const RUNTIME_EXCEPTION = '服务器运行期异常 运行时异常 运行异常 未检查异常！';
    const ERROR_EXCEPTION = '服务器框架运行出错！';
    const INVALID_ARGUMENT_EXCEPTION = '数据库链接问题！';
    const MODEL_NOT_FOUND_EXCEPTION = '数据模型错误！';
    const QUERY_EXCEPTION = '数据库DB错误！';

    const COMMON_EXCEPTION = '网络错误！';
    const API_ERROR_EXCEPTION = '操作失败！';
    const ADD_API_ERROR = '添加失败！';
    const ADD_API_SUCCESS = '添加成功！';
    const UPDATE_API_ERROR = '修改失败！';
    const UPDATE_API_SUCCESS = '修改成功！';
    const STATUS_API_ERROR = '调整失败！';
    const STATUS_API_SUCCESS = '调整成功！';

    const DELETE_API_ERROR = '删除失败！';
    const DELETE_API_SUCCESS = '删除成功！';

    const DELETE_RECYCLE_API_ERROR = '恢复失败！';
    const DELETE_RECYCLE_API_SUCCESS = '恢复成功！';

    const TOKEN_ERROR_KEY = 'apikey错误！';     // 70001
    const TOKEN_ERROR_SET = '请先登录！';        // 70002
    const TOKEN_ERROR_BLACK = 'token 被拉黑！';  // 70003
    const TOKEN_ERROR_EXPIRED = 'token 过期！';  // 70004
    const TOKEN_ERROR_JWT = '请先登录！';         //  70005
    const TOKEN_ERROR_JTB = '请先登录！';          // 70006
}
