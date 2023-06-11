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
 * @Name 数据看板控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/7/3 17:08
 */

namespace Modules\Blog\Http\Controllers\v1;


use Modules\Blog\Services\dashboard\DashboardService;

class DashboardController extends BaseApiController
{
    public function index(){
        return (new DashboardService())->index();
    }
}
