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
 * @Name 系统配置
 * @Description
 * @Auther hengcool
 * @Date 2021/6/21 15:09
 */

namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Admin\Services\config\ConfigService;

class ConfigController extends BaseApiController
{
    /**
     * @name 配置页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @method  GET
     * @return JSON
     **/
    public function index()
    {
        return (new ConfigService())->index();
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/21 15:35
     * @method  PUT
     * @param  name String 站点名称
     * @param  image_status Int 图片储存:1=本地,2=七牛云
     * @param  logo_id Int 站点logo
     * @param  about_us String 关于我们
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new ConfigService())->update($request->only(['name','image_status','logo_id','about_us']));
    }
}
