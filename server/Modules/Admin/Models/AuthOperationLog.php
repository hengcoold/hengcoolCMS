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
 * @Name 操作日志模型
 * @Description
 * @Auther hengcool
 * @Date 2021/6/23 10:14
 */

namespace Modules\Admin\Models;


class AuthOperationLog extends BaseApiModel
{
    /**
     * @name 关联管理员
     * @description 多对一关系
     * @author hengcool
     * @date 2021/6/23 15:04
     * @return JSON
     **/
    public function admin_one()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthAdmin','admin_id','id');
    }
}
