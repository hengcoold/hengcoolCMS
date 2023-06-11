<?php

Route::group(["prefix"=>"v1/admin","middleware"=>"AdminApiAuth"],function (){
    //登录
    Route::post('login/login', 'v1\LoginController@login');
    // 获取管理员信息
    Route::get('admin/info', 'v1\IndexController@info');
    /***********************************首页***************************************/
    //刷新token
    Route::put('index/refreshToken', 'v1\IndexController@refreshToken');
    //退出登录
    Route::delete('index/logout', 'v1\IndexController@logout');
    //清除缓存
    Route::delete('index/outCache', 'v1\IndexController@outCache');
    //修改密码
    Route::put('index/upadtePwdView', 'v1\IndexController@upadtePwdView');
    //获取模块
    Route::get('index/getModel', 'v1\IndexController@getModel');
    //获取左侧栏
    Route::get('index/getMenu', 'v1\IndexController@getMenu');

    //单图上传
    Route::post('upload/fileImage', 'v1\UploadController@fileImage');

    //图片列表
    Route::get('upload/getImageList', 'v1\UploadController@getImageList');
    //获取平台信息
    Route::get('index/getMain', 'v1\IndexController@getMain');
    // 获取地区数据
    Route::get('index/getAreaData', 'v1\IndexController@getAreaData');
    // 转换编辑器内容
    Route::post('index/setContent', 'v1\IndexController@setContent');
    /***********************************管理员列表***************************************/
    //列表数据
    Route::get('admin/index', 'v1\AdminController@index');
    //获取权限组
    Route::get('admin/getGroupList', 'v1\AdminController@getGroupList');
    //获取项目列表
    Route::get('admin/getProjectList', 'v1\AdminController@getProjectList');
    //添加
    Route::post('admin/store', 'v1\AdminController@store');
    //编辑页面
    Route::get('admin/edit', 'v1\AdminController@edit');
    //编辑提交
    Route::put('admin/update', 'v1\AdminController@update');
    //调整状态
    Route::put('admin/status', 'v1\AdminController@status');
    //初始化密码
    Route::put('admin/updatePwd', 'v1\AdminController@updatePwd');

    /***********************************权限组列表***************************************/
    //列表数据
    Route::get('group/index', 'v1\GroupController@index');
    //添加
    Route::post('group/store', 'v1\GroupController@store');
    //编辑页面
    Route::get('group/edit', 'v1\GroupController@edit');
    //编辑提交
    Route::put('group/update', 'v1\GroupController@update');
    //调整状态
    Route::put('group/status', 'v1\GroupController@status');
    //分配权限
    Route::get('group/access', 'v1\GroupController@access');
    //分配权限提交
    Route::put('group/accessUpdate', 'v1\GroupController@accessUpdate');

    /***********************************菜单管理***************************************/
    //列表数据
    Route::get('rule/index', 'v1\RuleController@index');
    //添加
    Route::post('rule/store', 'v1\RuleController@store');
    // 添加子级返回父级id
    Route::get('rule/pidArr', 'v1\RuleController@pidArr');
    //编辑页面
    Route::get('rule/edit', 'v1\RuleController@edit');
    //编辑提交
    Route::put('rule/update', 'v1\RuleController@update');
    //菜单状态
    Route::put('rule/status', 'v1\RuleController@status');
    //是否验证权限
    Route::put('rule/open', 'v1\RuleController@open');
    // 固定面板
    Route::put('rule/affix', 'v1\RuleController@affix');
    //排序
    Route::put('rule/sorts', 'v1\RuleController@sorts');
    //删除
    Route::delete('rule/cDestroy', 'v1\RuleController@cDestroy');

    /***********************************系统配置***************************************/
    //系统配置
    Route::get('config/index', 'v1\ConfigController@index');
    //提交
    Route::put('config/update', 'v1\ConfigController@update');
    /***********************************地区列表***************************************/
    //地区列表
    Route::get('area/index', 'v1\AreaController@index');
    //添加
    Route::post('area/store', 'v1\AreaController@store');
    //编辑页面
    Route::get('area/edit', 'v1\AreaController@edit');
    //编辑提交
    Route::put('area/update', 'v1\AreaController@update');
    //状态
    Route::put('area/status', 'v1\AreaController@status');
    //排序
    Route::put('area/sorts', 'v1\AreaController@sorts');
    //删除
    Route::delete('area/cDestroy', 'v1\AreaController@cDestroy');
    //导入服务器数据
    Route::get('area/importData', 'v1\AreaController@importData');
    // 写入地区缓存
    Route::post('area/setAreaData', 'v1\AreaController@setAreaData');
    /***********************************操作日志***************************************/
    //操作日志
    Route::get('operationLog/index', 'v1\OperationLogController@index');
    //删除
    Route::delete('operationLog/cDestroy', 'v1\OperationLogController@cDestroy');
    //批量删除
    Route::delete('operationLog/cDestroyAll', 'v1\OperationLogController@cDestroyAll');
    /***********************************数据库管理***************************************/
    //数据表管理
    Route::get('dataBase/index','v1\DataBaseController@index');
    // 表详情
    Route::get('dataBase/tableData', 'v1\DataBaseController@tableData');
    // 备份表
    Route::post('dataBase/backUp', 'v1\DataBaseController@backUp');
    // 备份列表
    Route::get('dataBase/restoreData', 'v1\DataBaseController@restoreData');
    // 查询文件详情
    Route::get('dataBase/getFiles', 'v1\DataBaseController@getFiles');
    // 删除
    Route::delete('dataBase/delSqlFiles', 'v1\DataBaseController@delSqlFiles');
    /***********************************数据看板***************************************/
    //数据看板
    Route::get('index/dashboard','v1\IndexController@dashboard');
    // 接口请求图表数据
    Route::get('index/getLogCountList','v1\IndexController@getLogCountList');
    /***********************************项目管理***************************************/
    //项目管理
    Route::get('project/index', 'v1\ProjectController@index');
    //添加
    Route::post('project/store', 'v1\ProjectController@store');
    //编辑页面
    Route::get('project/edit', 'v1\ProjectController@edit');
    //编辑提交
    Route::put('project/update', 'v1\ProjectController@update');
    //调整状态
    Route::put('project/status', 'v1\ProjectController@status');
    /***********************************会员管理***************************************/
    //会员管理
    Route::get('user/index', 'v1\UserController@index');
    //添加
    Route::post('user/store', 'v1\UserController@store');
    //编辑页面
    Route::get('user/edit', 'v1\UserController@edit');
    //编辑提交
    Route::put('user/update', 'v1\UserController@update');
    //调整状态
    Route::put('user/status', 'v1\UserController@status');
    //初始化密码
    Route::put('user/updatePwd', 'v1\UserController@updatePwd');
});

