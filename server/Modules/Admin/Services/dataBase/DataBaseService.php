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
 * @Name 数据库管理服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/23 16:54
 */

namespace Modules\Admin\Services\dataBase;


use Modules\Admin\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
class DataBaseService extends BaseApiService
{
    /**
     * @name 列表
     * @description
     * @author hengcool
     * @date 2021/6/23 16:55
     * @param  name String 表名
     * @return JSON
     **/
    public function index($name)
    {
        $where = config('database.connections.mysql')['prefix'];
        if($name){
            $where = $name;
        }
        $tables = DB::select("SHOW TABLE STATUS LIKE '" . $where ."%'");
        $total = 0;
        $arr = [];
        //便利表
        foreach ($tables as $k => $v){
            //调用PHP格式化字节大小函数
            $arr[$k]['Name'] = $v->Name;//数据表名
            $arr[$k]['Rows'] = $v->Rows;//数据条数
            $arr[$k]['Engine'] = $v->Engine;//类型
            $arr[$k]['Collation'] = $v->Collation;//排序规则
            $arr[$k]['Create_time'] = $v->Create_time;//创建时间
            $arr[$k]['Update_time'] = $v->Update_time;//更新时间
            $arr[$k]['Comment'] = $v->Comment;//备注
            $arr[$k]['size'] = $this->formatBytes($v->Data_length+$v->Index_length);//占用空间
            $total += $v->Data_length+$v->Index_length;
        }
        return $this->apiSuccess('',[
            'data'=>$arr,
            'tableNum'=>count($arr),
            'total'=>$this->formatBytes($total)
        ]);
    }
    /**
     * @name 表详情
     * @description
     * @author hengcool
     * @date 2021/6/23 17:22
     * @param table  String 表名
     * @return JSON  SCHEMA table_schema = `` and
     **/
    public function tableData(string $table){
        $database = config('database.connections.mysql')['database'];
        $table = DB::select("select * from information_schema.COLUMNS where table_schema= "."'".$database."'"." and table_name="."'".$table."'");
        $arr = [];
        //便利表
        foreach ($table as $k => $v){
            //调用PHP格式化字节大小函数
            $arr[$k]['COLUMN_NAME'] = $v->COLUMN_NAME;//字段名
            $arr[$k]['COLUMN_TYPE'] = $v->COLUMN_TYPE;//数据类型
            $arr[$k]['COLUMN_DEFAULT'] = $v->COLUMN_DEFAULT;//默认值
            $arr[$k]['IS_NULLABLE'] = $v->IS_NULLABLE;//允许非空
            $arr[$k]['EXTRA'] = ($v->EXTRA == 'auto_increment'?'是':' ');//自动递增
            $arr[$k]['COLUMN_COMMENT'] = $v->COLUMN_COMMENT;//备注
        }
        return $this->apiSuccess('',[
            'data'=>$arr,
            'tableNum'=>count($arr)
        ]);
    }
    /**
     * @name 备份表
     * @description
     * @author hengcool
     * @date 2021/6/23 17:29
     * @param table  String 表名
     * @return JSON
     **/
    public function backUp($tables){
        if(empty($tables)){
            $dataList = DB::select("SHOW TABLE STATUS LIKE '" . config('database.connections.mysql')['prefix'] ."%'");
            foreach ($dataList as $row){
                $tables[]= $row->Name;
            }
        }
        //输出文件头部信息
        $sql = "-- SONGBO SQL Backup\n-- Time:".date('Y-m-d H:i:s')."\n-- 备份数据库 \n\n";
        foreach($tables as $key=>$table){
            //表信息
            $sql .= "--\n-- 表的结构 `$table`\n-- \n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            //打开表
            $info = DB::select("SHOW CREATE TABLE  $table");
            //查出表结构
            $sql .= str_replace(array('USING BTREE','ROW_FORMAT=DYNAMIC'),'',json_decode(json_encode($info), true)[0]['Create Table']).";\n";
            //打开表
            $result = DB::select("SELECT * FROM $table");
            $result = json_decode(json_encode($result), true);
            //判断表中是否有数据
            if($result){
                $sql .= "\n-- \n-- 导出`$table`表中的数据 `$table`\n--\n";
                //便利数据
                foreach($result as $key=>$val) {
                    foreach ($val as $k=>$field){
                        if(is_string($field)) {
                            $val[$k] = '\''. $field.'\'';
                        }elseif($field==0){
                            $val[$k] = 0;
                        } elseif(empty($field)){
                            $val[$k] = 'NULL';
                        }
                    }
                    //将数据存入$sql中
                    $sql .= "INSERT INTO `$table` VALUES (".implode(',', $val).");\n";
                }
            }
        }
        //打开文件存入数据
        $r= file_put_contents(public_path('data') . '/' . date('YmdHis') . rand(1000000,9999999) . '.sql', trim($sql));
        return $this->apiSuccess('备份数据库成功！');
    }
    /**
     * @name 备份文件列表
     * @description
     * @author hengcool
     * @date 2021/6/23 18:09
     * @return JSON
     **/
    public function restoreData(){
        //定义查询文件后缀
        $pattern = "*.sql";
        //glob() 函数返回匹配指定模式的文件名或目录。
        //该函数返回一个包含有匹配文件 / 目录的数组。如果出错返回 false。
        $fileList = glob(public_path('data') . '/' .$pattern);
        $fileArray = array();
        $http = $this->getHttp();
        foreach ($fileList  as $i => $file) {
            //只读取文件
            if (is_file($file)){
                //取得文件大小
                $_size = filesize($file);
                //函数返回路径中的文件名部分
                $name = basename($file);
                $fileArray[] = array(
                    'name' => $name,
                    'time' => date('Y-m-d H:i:s',filemtime($file)),
                    'sortSize' => $this->formatBytes($_size),
                    'size' => $_size,
                    'infoLoading'=>false,
                    'url'=>$http.'/data/'.$name
                );
            }
        }
        //判断文件是否存在
        if(empty($fileArray)) $fileArray = array();
        return $this->apiSuccess('',$fileArray);
    }
    /**
     * @name 读取文件内容
     * @description
     * @author hengcool
     * @date 2021/6/23 18:19
     * @param name String   文件名称
     * @return JSON
     **/
    public function getFiles(string $name)
    {
        $info = @file_get_contents(public_path('data') . '/' . $name);
        if($info){
            return $this->apiSuccess('',[
                'info'=>$info
            ]);
        }
        $this->apiError();
    }
    /**
     * @name 删除sql文件
     * @description
     * @author hengcool
     * @date 2021/6/23 18:19
     * @param name String   文件名称
     * @return JSON
     **/
    public function delSqlFiles(string $name)
    {
        if(unlink(public_path('data') . '/' . $name)){
            return $this->apiSuccess('删除文件成功！');
        }
        $this->apiError();
    }
}
