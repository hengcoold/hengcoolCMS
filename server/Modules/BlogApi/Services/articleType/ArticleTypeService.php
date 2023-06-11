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
 * @Name 文章分类服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 15:34
 */

namespace Modules\BlogApi\Services\articleType;




use Modules\BlogApi\Models\BlogArticleType;
use Modules\BlogApi\Services\BaseApiService;

class ArticleTypeService extends BaseApiService
{
    public function typeList(){
        $model = BlogArticleType::query();
        $list = $model->select('id','name','pid')
            ->where(['status'=>1,'project_id'=>$this->projectId])
            ->orderBy('sort','asc')->orderBy('id','desc')
            ->get()->toArray();
        return $this->apiSuccess('',$this->tree($list));
    }
}
