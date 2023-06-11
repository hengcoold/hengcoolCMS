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
 * @Name 当前模块服务基类
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 13:21
 */

namespace Modules\BlogApi\Services;


use Modules\BlogApi\Models\AuthProject;
use Modules\Common\Services\BaseService;
class BaseApiService extends BaseService
{
    protected $projectId = '';
    public function __construct()
    {
        $baseHttp = request()->header('basehttp');
        $id = AuthProject::query()->where(['url'=>$baseHttp])->value('id');
        if(!$id){
          $this->apiError('项目不存在！');
        }
        $this->projectId = $id;
        parent::__construct();
    }
}
