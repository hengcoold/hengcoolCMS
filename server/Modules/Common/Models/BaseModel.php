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
 * @Name  模型基类
 * @Description  用于所有的数据库定义基类
 * @Auther hengcool
 * @Date 2021/6/11 10:31
 */

namespace Modules\Common\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class BaseModel extends EloquentModel
{
    /**
     * @name
     * @description
     * @author hengcool
     * @date 2021/6/11 10:44
     * @method  GET
     * @param
     * @return JSON
     **/
    protected $primaryKey = 'id';
    /**
     * @name id是否自增
     * @description
     * @author hengcool
     * @date 2021/6/11 12:25
     * @return Bool
     **/
    public $incrementing = false;
    /**
     * @name   表id是否为自增
     * @description
     * @author hengcool
     * @date 2021/6/11 12:26
     * @return String
     **/
    protected $keyType = 'int';
    /**
     * @name 指示是否自动维护时间戳
     * @description
     * @author hengcool
     * @date 2021/6/11 10:36
     * @return Bool
     **/
    public $timestamps = false;
    /**
     * @name 该字段可被批量赋值
     * @description
     * @author hengcool
     * @date 2021/6/11 10:40
     * @return Array
     **/
    protected $fillable = [];
    /**
     * @name 该字段不可被批量赋值
     * @description
     * @author hengcool
     * @date 2021/6/11 10:40
     * @return Array
     **/
    protected $guarded = [];

    /**
     * @name 时间格式传唤
     * @description
     * @author hengcool
     * @date 2021/6/17 16:20
     **/
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
