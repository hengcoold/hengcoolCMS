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
 * @Name  平台用户模型
 * @Description
 * @Auther hengcool
 * @Date 2021/6/29 14:27
 */

namespace Modules\Admin\Models;


class AuthUser extends BaseApiModel
{
    /**
     * @name 更新时间为null时返回
     * @description
     * @author hengcool
     * @date 2021/6/21 16:33
     * @param value String  $value
     * @return Boolean
     **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
    /**
     * @name 隐藏密码
     * @description
     * @author hengcool
     * @date 2021/6/29 14:30
     **/
    protected $hidden = [
        'password'
    ];

    /**
     * @name  关联省   多对一
     * @description
     * @author hengcool
     * @date 2021/6/12 3:12
     **/
    public function province_to()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthArea','province_id','id');
    }
    /**
     * @name  关联市   多对一
     * @description
     * @author hengcool
     * @date 2021/6/12 3:12
     **/
    public function city_to()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthArea','city_id','id');
    }
    /**
     * @name  关联区县   多对一
     * @description
     * @author hengcool
     * @date 2021/6/12 3:12
     **/
    public function county_to()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthArea','county_id','id');
    }
}
