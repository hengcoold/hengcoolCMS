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
 * @Name 图片管理规则模型
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 13:19
 */

namespace Modules\BlogApi\Models;


class BlogPic extends BaseApiModel
{
    /**
     * @name 更新时间为null时返回
     * @description
     * @author hengcool
     * @date 2021/6/28 13:20
     * @param int  $value
     * @return String
     **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
    /**
     * @name 关联图片
     * @description
     * @author hengcool
     * @date 2021/6/21 15:04
     * @return JSON
     **/
    public function image_one()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthImage','image_id','id');
    }
}
