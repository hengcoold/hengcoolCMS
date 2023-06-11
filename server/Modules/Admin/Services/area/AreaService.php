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
 * @Date 2021/6/12 03:07
 */

namespace Modules\Admin\Services\area;


use Modules\Admin\Models\AuthArea;
use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class AreaService extends BaseApiService
{
    /**
     * @name 地区列表
     * @description
     * @author hengcool
     * @date 2021/6/12 3:03
     * @param  pid int 父级Id
     * @return JSON
     **/
    public function index(int $pid)
    {
        $list = AuthArea::orderBy('sort','asc')
            ->orderBy('id','asc')
            ->where('pid',$pid)
            ->get()
            ->toArray();
        foreach($list as $k=> $v){
            $list[$k]['hasChildren'] = true;
        }
        return $this->apiSuccess('',$list);
    }

    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/12 3:29
     * @param  data Array 添加数据
     * @param  data.pid Int 父级ID
     * @param  data.name String 名称
     * @param  data.short_name String 简称
     * @param  data.level_type Int 级别
     * @param  data.city_code Int 区号
     * @param  data.zip_code int 邮编
     * @param  data.lng String 经度
     * @param  data.lat String 纬度
     * @param  data.pinyin String 拼音
     * @param  data.status Int 显示状态:0=隐藏,1=显示
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function store(array $data)
    {
        return $this->commonCreate(AuthArea::query(),$data);
    }
    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function edit(int $id){
       $data = AuthArea::find($id)->toArray();
       if($data['pid']){
           $data['pid_name'] = AuthArea::where('id',$data['pid'])->value('name');
        }
        return $this->apiSuccess('',$data);
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 地区id
     * @param  data.pid Int 父级ID
     * @param  data.name String 名称
     * @param  data.short_name String 简称
     * @param  data.level_type Int 级别
     * @param  data.city_code Int 区号
     * @param  data.zip_code int 邮编
     * @param  data.lng String 经度
     * @param  data.lat String 纬度
     * @param  data.pinyin String 拼音
     * @param  data.status Int 显示状态:0=隐藏,1=显示
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(AuthArea::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 管理员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(AuthArea::query(),$id,$data);
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 管理员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function sorts(int $id,array $data){
        return $this->commonSortsUpdate(AuthArea::query(),$id,$data);
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param id Int 权限id
     * @return JSON
     **/
    public function cDestroy(int $id){
        $idArr = $this->ids($id);
        return $this->commonDestroy(AuthArea::query(),$idArr);
    }
    /**
     * @name 获取删除id
     * @description
     * @author hengcool
     * @date 2021/6/14 10:14
     * @param id Int 当前删除数据id
     * @return Array
     **/
    private function ids(int $id):Array
    {
        $rule = AuthArea::select('id','pid')->get()->toArray();
        $arr = $this->delSort($rule,$id);
        $arr[] = $id;
        return $arr;
    }
    /**
     * @name 递归遍历数据
     * @description
     * @author hengcool
     * @date 2021/6/14 10:13
     * @param id Int 当前删除数据id
     * @param rule Array 列表信息
     * @return Array 返回获取当前的删除id的其他子id
     **/
    public function delSort(array $rule,int $id):Array
    {
        //创建新数组
        static $arr=array();
        foreach($rule as $k=>$v){
            if($v['pid'] == $id){
                $arr[] = $v['id'];
                unset($rule[$k]);
                $this->delSort($rule,$v['id']);
            }
        }
        return $arr;
    }
    /**
     * @name 导入服务器数据
     * @description
     * @author hengcool
     * @date 2021/6/21 16:36
     * @return JSON
     **/
    public function importData()
    {
        if(AuthArea::count()){
            $this->apiError('已有数据！');
        }
        header("Content-type:text/html;charset=utf-8");
        require base_path('Modules/Common/lib/PHPExcel').'/Classes/PHPExcel/IOFactory.php';
        $input_file_name = public_path('upload/area').'/省市区数据.xls';
        if (!file_exists($input_file_name)) {
            $this->apiError('数据文件不存在！');
        }
        date_default_timezone_set('PRC');
        try {
            $input_file_type = \PHPExcel_IOFactory::identify($input_file_name);
            $obj_reader = \PHPExcel_IOFactory::createReader($input_file_type);
            $obj_php_excel = $obj_reader->load($input_file_name);
        } catch(Exception $e) {
            $this->apiError('加载文件发生错误！');
        }
        $obj_php_excel = \PHPExcel_IOFactory::load($input_file_name);
        $sheet = $obj_php_excel->getSheet(0)->toArray();
        DB::beginTransaction();
        try{
            foreach($sheet as $k=>$v){
                if($k!=0){
                    $v = [
                        $sheet[0][0]=> $v[0],
                        $sheet[0][1]=> $v[1],
                        $sheet[0][2]=> $v[2],
                        $sheet[0][3]=> $v[3],
                        $sheet[0][4]=> $v[4],
                        $sheet[0][5]=> $v[5],
                        $sheet[0][6]=> $v[6],
                        $sheet[0][7]=> $v[7],
                        $sheet[0][8]=> $v[8],
                        $sheet[0][9]=> $v[9],
                        $sheet[0][10]=> $v[10],
                        $sheet[0][11]=> $v[11],
                    ];
                    if(!$v['city_code']){
                        unset($v['city_code']);
                    }
                    if(!$v['zip_code']){
                        unset($v['zip_code']);
                    }
                    AuthArea::insert($v);
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            $this->apiError('导入数据失败！');
        }
        return $this->apiSuccess('导入数据成功！');
    }
    /**
     * @name 写入地区缓存
     * @description
     * @author hengcool
     * @date 2021/6/12 3:03
     * @return JSON
     **/
    public function setAreaData()
    {
        $list = AuthArea::orderBy('sort','asc')
            ->orderBy('id','asc')
            ->where('status',1)
            ->get()
            ->toArray();
        if(count($list)){
            $list = $this->tree($list);
            Cache::put('areaList',$list);
        }
        return $this->apiSuccess('写入地区缓存成功！');
    }
}
