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
 * @Name 系统配置模型
 * @Description
 * @Auther hengcool
 * @Date 2021/6/30 09:25
 */

namespace Modules\Blog\Models;


class AuthProject extends BaseApiModel
{
    /**
     * @name   更新时间为null时返回
     * @param  int  $value
     * @return Boolean
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
    /**
     * @name 关联站点logo图片
     * @description 多对一
     * @author hengcool
     * @date 2021/6/21 15:04
     * @return JSON
     **/
    public function logo_one()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthImage','logo_id','id');
    }
    /**
     * @name 站点标识
     * @description 多对一
     * @author hengcool
     * @date 2021/6/21 15:04
     * @return JSON
     **/
    public function ico_one()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthImage','ico_id','id');
    }
}
