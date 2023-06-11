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
 * @Name 地区管理
 * @Description
 * @Auther hengcool
 * @Date 2021/6/21 15:44
 */

namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonIdRequest;
use Modules\Admin\Http\Requests\CommonPidRequest;
use Modules\Admin\Http\Requests\CommonSortRequest;
use Modules\Admin\Http\Requests\CommonStatusRequest;
use Modules\Admin\Services\area\AreaService;

class AreaController extends BaseApiController
{
    /**
     * @name 地区列表
     * @description
     * @author hengcool
     * @date 2021/6/21 15:47
     * @method  GET
     * @param  pid int 父级Id
     * @return JSON
     **/
    public function index(CommonPidRequest $request)
    {
        return (new AreaService())->index($request->get('pid'));
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/12 3:29
     * @method  POST
     * @param  pid Int 父级ID
     * @param  name String 名称
     * @param  short_name String 简称
     * @param  level_type Int 级别
     * @param  city_code Int 区号
     * @param  zip_code int 邮编
     * @param  lng String 经度
     * @param  lat String 纬度
     * @param  pinyin String 拼音
     * @param  status Int 显示状态:0=隐藏,1=显示
     * @param  sort Int 排序
     * @return JSON
     **/
    public function store(Request $request)
    {
        return (new AreaService())->store($request->only([
            'pid',
            'name',
            'short_name',
            'level_type',
            'city_code',
            'zip_code',
            'lng',
            'lat',
            'pinyin',
            'status',
            'sort',
        ]));
    }

    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @method  GET
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new AreaService())->edit($request->get('id'));
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:02
     * @method  PUT
     * @param  id Int Id
     * @param  pid Int 父级ID
     * @param  name String 名称
     * @param  short_name String 简称
     * @param  level_type Int 级别
     * @param  city_code Int 区号
     * @param  zip_code int 邮编
     * @param  lng String 经度
     * @param  lat String 纬度
     * @param  pinyin String 拼音
     * @param  status Int 显示状态:0=隐藏,1=显示
     * @param  sort Int 排序
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new AreaService())->update($request->get('id'),$request->only([
            'pid',
            'name',
            'short_name',
            'level_type',
            'city_code',
            'zip_code',
            'lng',
            'lat',
            'pinyin',
            'status',
            'sort',
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 3:49
     * @method  PUT
     * @param  id Int 管理员id
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new AreaService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/14 10:02
     * @method  PUT
     * @param  id Int 权限id
     * @param  sort Int 排序
     * @return JSON
     **/
    public function sorts(CommonSortRequest $request)
    {
        return (new AreaService())->sorts($request->get('id'),$request->only([
            'sort'
        ]));
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param id Int 权限id
     * @return JSON
     **/
    public function cDestroy(CommonIdRequest $request)
    {
        return (new AreaService())->cDestroy($request->get('id'));
    }
    /**
     * @name 导入服务器数据
     * @description
     * @author hengcool
     * @date 2021/6/21 16:36
     * @method  GET
     * @return JSON
     **/
    public function importData()
    {
        return (new AreaService())->importData();
    }

    /**
     * @name 写入地区缓存
     * @description
     * @author hengcool
     * @date 2021/6/21 16:36
     * @method  POST
     * @return JSON
     **/
    public function setAreaData()
    {
        return (new AreaService())->setAreaData();
    }
}
