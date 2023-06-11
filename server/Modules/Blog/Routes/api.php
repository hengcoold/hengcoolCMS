<?php

Route::group(["prefix"=>"v1/blog","middleware"=>"AdminApiAuth"],function (){
    /***********************************数据看板***************************************/
    //数据看板
    Route::get('dashboard/index', 'v1\DashboardController@index');

    /***********************************用户级别规则***************************************/
    //列表
    Route::get('userLevel/index', 'v1\UserLevelController@index');
    //添加
    Route::post('userLevel/store', 'v1\UserLevelController@store');
    //编辑页面
    Route::get('userLevel/edit', 'v1\UserLevelController@edit');
    //编辑提交
    Route::put('userLevel/update', 'v1\UserLevelController@update');
    //调整状态
    Route::put('userLevel/status', 'v1\UserLevelController@status');
    //排序
    Route::put('userLevel/sorts', 'v1\UserLevelController@sorts');
    // 删除
    Route::delete('userLevel/cDestroy', 'v1\UserLevelController@cDestroy');
    /***********************************用户经验值规则***************************************/
    //列表
    Route::get('empiricalValue/index', 'v1\EmpiricalValueController@index');
    //添加
    Route::post('empiricalValue/store', 'v1\EmpiricalValueController@store');
    //编辑页面
    Route::get('empiricalValue/edit', 'v1\EmpiricalValueController@edit');
    //编辑提交
    Route::put('empiricalValue/update', 'v1\EmpiricalValueController@update');
    //调整状态
    Route::put('empiricalValue/status', 'v1\EmpiricalValueController@status');
    //排序
    Route::put('empiricalValue/sorts', 'v1\EmpiricalValueController@sorts');
    // 删除
    Route::delete('empiricalValue/cDestroy', 'v1\EmpiricalValueController@cDestroy');

    /***********************************标签管理***************************************/
    //列表
    Route::get('label/index', 'v1\LabelController@index');
    //添加
    Route::post('label/store', 'v1\LabelController@store');
    //编辑页面
    Route::get('label/edit', 'v1\LabelController@edit');
    //编辑提交
    Route::put('label/update', 'v1\LabelController@update');
    //调整状态
    Route::put('label/status', 'v1\LabelController@status');
    //排序
    Route::put('label/sorts', 'v1\LabelController@sorts');
    // 删除
    Route::delete('label/cDestroy', 'v1\LabelController@cDestroy');
    /***********************************文章分类***************************************/
    //列表
    Route::get('articleType/index', 'v1\ArticleTypeController@index');
    //添加
    Route::post('articleType/store', 'v1\ArticleTypeController@store');
    //添加子级返回父级id
    Route::get('articleType/pidArr', 'v1\ArticleTypeController@pidArr');
    //编辑页面
    Route::get('articleType/edit', 'v1\ArticleTypeController@edit');
    //编辑提交
    Route::put('articleType/update', 'v1\ArticleTypeController@update');
    //调整状态
    Route::put('articleType/status', 'v1\ArticleTypeController@status');
    //排序
    Route::put('articleType/sorts', 'v1\ArticleTypeController@sorts');
    // 删除
    Route::delete('articleType/cDestroy', 'v1\ArticleTypeController@cDestroy');

    /***********************************文章管理***************************************/
    //列表
    Route::get('article/index', 'v1\ArticleController@index');
    //获取文章分类列表
    Route::get('article/getArticleTypeList', 'v1\ArticleController@getArticleTypeList');
    //获取文章标签列表
    Route::get('article/getLabelList', 'v1\ArticleController@getLabelList');
    //获取用户列表
    Route::get('article/getUserList', 'v1\ArticleController@getUserList');
    //添加
    Route::post('article/store', 'v1\ArticleController@store');
    //编辑页面
    Route::get('article/edit', 'v1\ArticleController@edit');
    //编辑提交
    Route::put('article/update', 'v1\ArticleController@update');
    //调整状态
    Route::put('article/status', 'v1\ArticleController@status');
    //是否推荐
    Route::put('article/open', 'v1\ArticleController@open');
    //排序
    Route::put('article/sorts', 'v1\ArticleController@sorts');
    // 删除
    Route::delete('article/cDestroy', 'v1\ArticleController@cDestroy');
    // 批量删除
    Route::delete('article/cDestroyAll', 'v1\ArticleController@cDestroyAll');
    //生成标题 摘要 标签
    Route::post('article/generateContent', 'v1\ArticleController@generateContent');
    /***********************************系统配置***************************************/
    //系统配置
    Route::get('project/index', 'v1\ProjectController@index');
    //提交
    Route::put('project/update', 'v1\ProjectController@update');
    /***********************************会员管理***************************************/
    //会员管理
    Route::get('user/index', 'v1\UserController@index');
    //获取平台会员列表
    Route::get('user/getUserList', 'v1\UserController@getUserList');
    //添加
    Route::post('user/store', 'v1\UserController@store');
    //编辑页面
    Route::get('user/edit', 'v1\UserController@edit');
    //编辑提交
    Route::put('user/update', 'v1\UserController@update');
    //调整状态
    Route::put('user/status', 'v1\UserController@status');

    /***********************************会员关注***************************************/
    //会员关注
    Route::get('user/attentionIndex', 'v1\UserController@attentionIndex');

    /***********************************文章回收站***************************************/
    // 文章回收站列表
    Route::get('article/recycleIndex', 'v1\ArticleController@recycleIndex');
    // 恢复
    Route::put('article/recycle', 'v1\ArticleController@recycle');
    // 批量恢复
    Route::put('article/recycleAll', 'v1\ArticleController@recycleAll');
    /***********************************文章评论***************************************/
    // 文章评论
    Route::get('articleComment/index', 'v1\ArticleCommentController@index');
    //调整状态
    Route::put('articleComment/status', 'v1\ArticleCommentController@status');
    //排序
    Route::put('articleComment/sorts', 'v1\ArticleCommentController@sorts');
    // 删除评论
    Route::delete('articleComment/cDestroy', 'v1\ArticleCommentController@cDestroy');
    /***********************************文章点赞***************************************/
    // 文章点赞
    Route::get('articleLike/index', 'v1\ArticleLikeController@index');
    /***********************************文章收藏***************************************/
    // 文章收藏
    Route::get('articleCollect/index', 'v1\ArticleCollectController@index');


    /***********************************图片管理***************************************/
    //列表
    Route::get('pic/index', 'v1\PicController@index');
    //添加
    Route::post('pic/store', 'v1\PicController@store');
    //编辑页面
    Route::get('pic/edit', 'v1\PicController@edit');
    //编辑提交
    Route::put('pic/update', 'v1\PicController@update');
    //调整状态
    Route::put('pic/status', 'v1\PicController@status');
    //排序
    Route::put('pic/sorts', 'v1\PicController@sorts');
    // 删除
    Route::delete('pic/cDestroy', 'v1\PicController@cDestroy');
});
