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
 * @Date 2021/6/21 15:18
 */

namespace Modules\Admin\Services\config;


use Modules\Admin\Models\AuthConfig;
use Modules\Admin\Services\auth\TokenService;
use Modules\Admin\Services\BaseApiService;

class ConfigService extends BaseApiService
{
    /**
     * @name 配置页面
     * @description
     * @author hengcool
     * @date 2021/6/21 15:34
     * @return JSON
     **/
    public function index(){
        $info = AuthConfig::select('id','name','image_status','logo_id','about_us')->with([
            'logo_one'=>function($query){
                $query->select('id','url','open');
            }
        ])->find(1)->toArray();
        //        replace_pic_url
        if($info['logo_one']['open'] == 1){
            $info['logo_url'] = $this->getHttp().$info['logo_one']['url'];
        }else{
            $info['logo_url'] = $info['logo_one']['url'];
        }
        $info['about_us'] = $this->getReplacePicUrl($info['about_us']);
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/21 15:38
     * @param  data Array 修改数据
     * @param  data.name String 站点名称
     * @param  data.image_status Int 图片储存:1=本地,2=七牛云
     * @param  data.logo_id Int 站点logo
     * @param  data.about_us String 关于我们
     * @return JSON
     **/
    public function update(array $data){
        $data['about_us'] = $this->getRemvePicUrl($data['about_us']);
        return $this->commonUpdate(AuthConfig::query(),1,$data);
    }





}
