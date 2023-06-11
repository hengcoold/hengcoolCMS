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
 * @Name 文章管理模型
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 16:33
 */

namespace Modules\Blog\Models;


class BlogArticle extends BaseApiModel
{
    /**
     * @name 更新时间为null时返回
     * @description
     * @author hengcool
     * @date 2021/6/28 13:20
     * @method  GET
     * @param String  $value
     * @return String
     **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
    /**
     * @name 关联图片
     * @description 多对一
     * @author hengcool
     * @date 2021/6/28 17:32
     * @return JSON
     **/
    public function image_one()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthImage','image_id','id');
    }
    /**
     * @name 关联分类
     * @description 多对一
     * @author hengcool
     * @date 2021/6/28 17:32
     * @return JSON
     **/
    public function type_to()
    {
        return $this->belongsTo('Modules\Blog\Models\BlogArticleType','type_id','id');
    }
    /**
     * @name 关联用户
     * @description 多对一
     * @author hengcool
     * @date 2021/6/28 17:32
     * @return JSON
     **/
    public function user_to()
    {
        return $this->belongsTo('Modules\Blog\Models\BlogUserInfo','user_id','id');
    }
    /**
     * @name 关联评论
     * @description 一对多
     * @author hengcool
     * @date 2021/6/28 17:33
     * @return JSON
     **/
    public function comment_many()
    {
        return $this->hasMany('Modules\Blog\Models\BlogArticleComment','article_id','id');
    }
    /**
     * @name 关联浏览量
     * @description 一对多
     * @author hengcool
     * @date 2021/6/28 17:33
     * @return JSON
     **/
    public function pv_many()
    {
        return $this->hasMany('Modules\Blog\Models\BlogArticlePv','article_id','id');
    }
    /**
     * @name 关联点赞
     * @description 一对多
     * @author hengcool
     * @date 2021/6/28 17:33
     * @return JSON
     **/
    public function like_many()
    {
        return $this->hasMany('Modules\Blog\Models\BlogArticleLike','article_id','id');
    }
    /**
     * @name 关联收藏
     * @description 一对多
     * @author hengcool
     * @date 2021/6/28 17:33
     * @return JSON
     **/
    public function collect_many()
    {
        return $this->hasMany('Modules\Blog\Models\BlogArticleCollect','article_id','id');
    }
    /**
     * @name 关联标签
     * @description  多对多
     * @author hengcool
     * @date 2021/6/28 17:33
     * @return JSON
     **/
    public function label_to()
    {
        return $this->belongsToMany('Modules\Blog\Models\BlogLabel', 'blog_article_labels', 'article_id', 'label_id')->withPivot(['article_id', 'label_id']);
    }
}
