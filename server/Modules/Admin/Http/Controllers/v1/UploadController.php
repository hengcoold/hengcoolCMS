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
 * @Name 图片管理
 * @Description
 * @Auther hengcool
 * @Date 2021/6/12 01:35
 */

namespace Modules\Admin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\upload\ImageService;
class UploadController extends BaseApiController
{
    /**
     * @name  图片上传
     * @description
     * @author hengcool
     * @date 2021/6/12 1:36
     * @method  POST
     * @param request Request 图片资源完整信息
     * @param request.file Resource  图片资源
     * @return JSON
     **/
    public function fileImage(Request $request){
        return (new ImageService())->fileImage($request);
    }
    /**
     * @name 图片列表
     * @description
     * @author hengcool
     * @date 2021/6/12 2:20
     * @method  GET
     * @param page int 页码
     * @param limit int 每页显示条数
     * @return JSON
     **/
    public function getImageList(CommonPageRequest $request){
        return (new ImageService())->getImageList($request->only(['page','limit']));
    }
}
