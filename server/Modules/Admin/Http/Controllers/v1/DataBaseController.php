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
 * @Name 数据库管理控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/6/23 16:50
 */

namespace Modules\Admin\Http\Controllers\v1;


use Modules\Admin\Http\Requests\CommonNameRequest;
use Modules\Admin\Http\Requests\TableRequest;
use Modules\Admin\Services\dataBase\DataBaseService;
use Illuminate\Http\Request;
class DataBaseController extends BaseApiController
{
    /**
     * @name 列表
     * @description
     * @author hengcool
     * @date 2021/6/23 16:52
     * @method  GET
     * @param  name String 表名
     * @return JSON
     **/
    public function index(Request $request)
    {
        return (new DataBaseService())->index($request->get('name'));
    }
    /**
     * @name 表详情
     * @description
     * @author hengcool
     * @date 2021/6/23 17:18
     * @method  GET
     * @param table  String 表名
     * @return JSON
     **/
    public function tableData(TableRequest $request)
    {
        return (new DataBaseService())->tableData($request->get('table'));
    }
    /**
     * @name 备份表
     * @description
     * @author hengcool
     * @date 2021/6/23 17:30
     * @method  GET
     * @param tables  Array 表名多个
     * @return JSON
     **/
    public function backUp(Request $request){
        return (new DataBaseService())->backUp($request->get('tables'));
    }
    /**
     * @name 备份文件列表
     * @description
     * @author hengcool
     * @date 2021/6/23 18:07
     * @method  GET
     * @return JSON
     **/
    public function restoreData(){
        return (new DataBaseService())->restoreData();
    }
    /**
     * @name 读取文件内容
     * @description
     * @author hengcool
     * @date 2021/6/23 18:16
     * @method  GET
     * @param name String   文件名称
     * @return JSON
     **/
    public function getFiles(CommonNameRequest $request) {
        return (new DataBaseService())->getFiles($request->get('name'));
    }
    /**
     * @name 删除sql文件
     * @description
     * @author hengcool
     * @date 2021/6/23 18:16
     * @method  DELETE
     * @param name String   文件名称
     * @return JSON
     **/
    public function delSqlFiles(CommonNameRequest $request) {
        return (new DataBaseService())->delSqlFiles($request->get('name'));
    }
}
