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
 * @Name 系统配置服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/11 23:21
 */

namespace Modules\Admin\Services\auth;


use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\AuthArea;
use Modules\Admin\Models\AuthConfig;
use Modules\Admin\Models\AuthOperationLog;
use Modules\Admin\Models\AuthProject;
use Modules\Admin\Models\AuthRule;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Models\AuthImage as AuthImageModel;
class ConfigService extends BaseApiService
{
    /**
     * @name 清除缓存
     * @description
     * @author hengcool
     * @date 2021/6/11 23:24
     * @return JSON
     **/
    public function outCache(){
//        Cache::forget('areaList');
        return $this->apiSuccess('清除成功！');
    }
    /**
     * @name 获取地区数据
     * @description
     * @author hengcool
     * @date 2021/6/11 23:24
     * @return JSON
     **/
    public function getAreaData(){
        $list = Cache::get('auth_rule');
        if(!$list){
            $list = AuthArea::orderBy('sort','asc')
                ->orderBy('id','asc')
                ->where('status',1)
                ->get()
                ->toArray();
            if(count($list)){
                $list = $this->tree($list);
                Cache::put('areaList',$list);
            }
        }
        return $this->apiSuccess('',$list);
    }
    /**
     * @name 获取平台信息
     * @description
     * @author hengcool
     * @date 2021/6/12 0:22
     * @return JSON
     **/
    public function getMain(){
        $info = AuthConfig::select('id','name','logo_id')->with([
            'logo_one'=>function($query){
                $query->select('id','url','open');
            }
        ])->find(1)->toArray();
        $http = $this->getHttp();
        if($info['logo_one']['open'] == 1){
            $info['logo_url'] = $http.$info['logo_one']['url'];
        }else{
            $info['logo_url'] = $info['logo_one']['url'];
        }
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 数据看板
     * @description
     * @author hengcool
     * @date 2021/6/24 16:35
     * @return JSON
     **/
    public function dashboard(){
        $v = "version()";
        $mysqlV= DB::select("select version()")[0]->$v;
        $info = [
            'model_count'=>AuthRule::where('type',1)->count(),//模块数量
            'menu_count'=>AuthRule::where('type',3)->count(),//可控菜单
            'project_count'=>AuthProject::count(),// 当前项目数
            'log_count'=>AuthOperationLog::count(), // 累计接口请求数
        ];
        $list = [
            [
                'name'=>'服务器版本',
                'value'=>php_uname('s').php_uname('r')
            ],
//            [
//                'name'=>'服务器CPU数量',
//                'value'=>$_SERVER['PROCESSOR_IDENTIFIER']
//            ],
//            [
//                'name'=>'服务器系统目录',
//                'value'=>$_SERVER['SystemRoot']
//            ],
            [
                'name'=>'服务域名',
                'value'=>$this->getHttp()
            ],
            [
                'name'=>'请求页面时通信协议的名称和版本',
                'value'=>$_SERVER['SERVER_PROTOCOL']
            ],
            // [
            //     'name'=>'服务IP地址',
            //     'value'=>$_SERVER['SERVER_ADDR']
            // ],
            // [
            //     'name'=>'服务端口',
            //     'value'=>$_SERVER['SERVER_PORT']
            // ],
            [
                'name'=>'服务器解译引擎',
                'value'=>$_SERVER['SERVER_SOFTWARE']
            ],
            [
                'name'=>'php版本',
                'value'=>PHP_VERSION
            ],
            [
                'name'=>'数据库版本',
                'value'=>config('database.default').$mysqlV
            ],
            [
                'name'=>'laravel版本',
                'value'=>app()::VERSION
            ],
            [
                'name'=>'项目路径',
                'value'=>DEFAULT_INCLUDE_PATH
            ],
            [
                'name'=>'PHP运行方式',
                'value'=>php_sapi_name()
            ],
            [
                'name'=>'最大上传限制',
                'value'=>get_cfg_var ("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许"
            ],
            [
                'name'=>'最大执行时间',
                'value'=>get_cfg_var("max_execution_time")."秒 "
            ],
            [
                'name'=>'脚本运行占用最大内存',
                'value'=>get_cfg_var ("memory_limit")?get_cfg_var("memory_limit"):"无"
            ]
        ];
        return $this->apiSuccess('',['list'=>$list,'info'=>$info]);
    }
    /**
     * @name 接口请求图表数据
     * @description
     * @author hengcool
     * @date 2021/6/25 16:49
     * @return JSON
     **/
    public function getLogCountList(){
        $data = AuthOperationLog::whereBetween('created_at',[date("Y-m-d",strtotime("-7 day")).' 00:00:00',date('Y-m-d'). ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date,COUNT(*) as value')
            ->groupBy('date')->get()->toArray();
        return $this->apiSuccess('',$data);
    }
    /**
     * @name 转换编辑器内容
     * @description
     * @author hengcool
     * @date 2021/7/1 10:53
     * @param  content String  编辑器内容
     * @return JSON
     **/
    public function setContentU(string $content){
        $http = $this->getHttp();
        $info = $this->content($content);
        $urlArr = $info['urlArr'];
        $arr = [];
        foreach($urlArr as $k=>$v){
            $image_id = AuthImageModel::insertGetId([
                'url'=>$v,
                'open'=>1,
                'status'=>0,
                'created_at'=> date('Y-m-d H:i:s')
            ]);
            $arr[] = [
                'id'=>$image_id,
                'url'=>$http.$v
            ];
        }
        $info['urlArr'] = $arr;
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 转换编辑器内容
     * @description
     * @author hengcool
     * @date 2021/7/1 11:07
     * @param  content String  编辑器内容
     * @return JSON
     **/
    public function content(string $content){
        $project_id = (new TokenService())->my()->project_id;
        preg_match_all('/<img (.*?)+src=[\'"](.*?)[\'"]/i',$content,$matches);
        $img = "";
        $replacements = [];
        if(!empty($matches)) {
            //注意，上面的正则表达式说明src的值是放在数组的第三个中
            $img = $matches[2];
        }
        if (!empty($img)) {
            $http = $this->getHttp();
            $patterns= array();
            $replacements = array();
            $date = date('YmdH');
            iconv("UTF-8", "GBK",$date);
            if(!file_exists(public_path('upload/images') . '/' . $project_id)){
                mkdir(public_path('upload/images') . '/' . $project_id . '',0777,true);
            }
            if(!file_exists(public_path('upload/images') . '/' . $project_id. '/content')){
                mkdir(public_path('upload/images') . '/' . $project_id. '/content',0777,true);
            }
            $dir = public_path('upload/images') . '/' . $project_id . '/content/' . $date;
            if (!file_exists($dir)){
                mkdir($dir,0777,true);
            }
            foreach($img as $imgItem){
                $url = $this->getRandStr(20).rand(1,99999).'.png';
                if($fileInfo = @file_get_contents(str_replace('&amp;','&',$imgItem))){
                    file_put_contents($dir.'/'.$url,$fileInfo);
                    $replacements[] = '/upload/images/' .$project_id . '/content/' . $date.'/'.$url;
                    $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
                    $patterns[] = $img_new;
                }
            }
            //让数组按照key来排序
            ksort($patterns);
            ksort($replacements);
            $replacementsArr = [];
            foreach ($replacements as $k=>$v){
                $replacementsArr[] = $http.$v;
            }
            //替换内容
            $content = preg_replace($patterns,$replacementsArr, $content);
        }
        return [
            'content'=>$content,
            'urlArr'=>$replacements
        ];
    }
}
