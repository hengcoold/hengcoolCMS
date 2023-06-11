<?php

namespace Modules\Admin\Models;
use Illuminate\Support\Facades\DB;
use zgldh\QiniuStorage\QiniuStorage;
use Modules\AuthAdmin\Services\CommonService;
class AuthImage extends BaseApiModel
{



	/**
	 * @name  图片类型
	 */
	public function image_type(){
		return [
			'1'=>'本地',
			'2'=>'七牛云'
		];
	}
	/**
	 * @name  处理列表数据
	 */
	public function image_list($data){
		$image_type = $this->image_type();
		foreach($data as $k=>$v){
			$data[$k]['open'] = $image_type[$v['open']];
		}
		return $data;
	}
	/**
	 * @name  图片列表删除方法
	 */
	public function image_destroy(){
		$arr = $this->where(['status'=>0])->get()->toArray();
		if(count($arr) > 0){
			$arr_id = array_column($arr,'id');
			if ($this->destroy($arr_id)){
				$filesystems = 'http://'.config('filesystems.disks')['qiniu']['domains']['default'].'/';
				$arr_qiniu = [];
				foreach($arr as $v){
					if($v['open'] == 1){
						unlink(base_path('public').$v['url']);
					}else if($v['open'] == 2){
						$arr_qiniu[] = trim(explode($filesystems,$v['url'])[1]);
					}
				}
				if($arr_qiniu){
					$disk = QiniuStorage::disk('qiniu');
					$disk->delete($arr_qiniu);
				}
				return response()->json([
					'status'=>1,
					'error' => '',
					'msg' => '清理成功！',
					'url'=> route('admin.image.index')
				],200);
			}else{
				return response()->json([
					'status'=>0,
					'error' => '清理失败！',
					'msg' => '',
					'url'=> route('admin.image.index')
				],201);
			}
		}else{
			return response()->json([
				'status'=>1,
				'error' => '',
				'msg' => '清理成功！',
				'url'=> route('admin.image.index')
			],200);
		}


	}
	/**
	 * @name  单图上传图片添加方法
	 * @param data 添加数据
	 * @param field 字段名
	 * @return 返回处理结果
	 */
	public function add_image($data,$field = 'image_id'){
		if(!isset($data[$field])){
			return $data;
		}
		if($this->where('id',$data[$field])->update(['status'=>1]) !== false){
			return $data;
		}else{
			return $this->api_error();
		};
	}

	/**
	 * @name  多图上传图片添加方法
	 * @param data 添加数据
	 * @param field 字段名
	 * @param file 需要删除的参数
	 * @return 返回处理结果
	 */
	public function add_image_all($data,$field,$file = ''){
		if($file != ''){
			unset($data[$file]);
		}
		if(!isset($data[$field])){
			return $data;
		}
		if($this->whereIn('id',$data[$field])->update(['status'=>1]) !== false){
			$data[$field] = implode('|',$data[$field]);
			return $data;
		}else{
			return response()->json([
				'status'=>0,
				'error' => config('admin.create_error'),
				'msg' => '',
			],201);
		};
	}

	/**
	 * @name  单图上传图片编辑方法
	 * @param table 表名
	 * @param id   表id
	 * @param data 添加数据
	 * @param field 字段名
	 * @return 返回处理结果
	 */
	public function edit_image($table,$id,$data,$field = 'image_id'){
		$image_id = DB::table($table)->where('id',$id)->value($field);
		DB::beginTransaction();
		try{
			if($image_id){
				$this->where('id',$image_id)->update(['status'=>0]);
			}
			if(isset($data[$field])){
				$this->where('id',$data[$field])->update(['status'=>1]);
			}else{
				$data[$field] = '';
			}
			DB::commit();
		}catch(\Exception $e){
			DB::rollBack();
			return $this->api_error();
		}
		return $data;
	}

	/**
	 * @name  多图上传图片编辑方法
	 * @param table 表名
	 * @param id   表id
	 * @param data 添加数据
	 * @param field 字段名
	 * @param file 需要删除的参数
	 * @return 返回处理结果
	 */
	public function edit_image_all($table,$id,$data,$field,$file = ''){
		if($file != ''){
			unset($data[$file]);
		}
		$images = DB::table($table)->where('id',$id)->value($field);
		DB::beginTransaction();
		try{
			if($images){
				$images = explode('|',$images);
				if(count($images)){
					$this->whereIn('id',$images)->update(['status'=>0]);
				}
			}
			if(isset($data[$field]) && count($data[$field])>0){
				$this->whereIn('id',$data[$field])->update(['status'=>1]);
				$data[$field] = implode('|',$data[$field]);
			}else{
				$data[$field] = '';
			}
			DB::commit();
		}catch(\Exception $e){
			DB::rollBack();
			return response()->json([
				'status'=>0,
				'error' => config('admin.update_error'),
				'msg' => '',
			],201);
		}

		return $data;
	}


	/**
	 * @name  单图上传图片删除方法
	 * @param table   表名
	 * @param id   主表id
	 * @return 返回处理结果
	 */
	public function del_image($table,$id){
		$image_id = DB::table($table)->where('id',$id)->value('image_id');
		if(empty($this->where('id',$image_id)->count()) || $this->where('id',$image_id)->update(['status'=>0])){
			return $id;
		}else{
			return response()->json([
				'status'=>0,
				'error' => config('admin.delete_error'),
				'msg' => '',
			],201);
		};
	}
	/**
	 * @name  多图上传图片删除方法
	 * @param table   表名
	 * @param id   主表id
	 * @param field   字段名
	 * @return 返回处理结果
	 */
	public function del_image_all($table,$id,$field){
		$images = DB::table($table)->where('id',$id)->value($field);
		if(empty($images)){
			return $id;
		}
		$images = explode('|',$images);
		if(empty($this->whereIn('id',$images)->count()) || $this->whereIn('id',$images)->update(['status'=>0])){
			return $id;
		}else{
			return response()->json([
				'status'=>0,
				'error' => config('admin.delete_error'),
				'msg' => '',
			],201);
		};
	}
	/**
	 * @name  单图上传图片批量删除方法
	 * @param id   主表id
	 * @param id   主表id
	 * @return 返回处理结果
	 */
	public function del_all_image($table,$ids){
		$image_id_arr = DB::table($table)->whereIn('id',$ids)->select('image_id')->get();
		$arr_id = [];
		foreach($image_id_arr as $v){
			$arr_id[] = $v->image_id;
		}
		if(count($arr_id) < 1){
			return $ids;
		}
		if($this->whereIn('id',$arr_id)->update(['status'=>0])){
			return $ids;
		}else{
			return response()->json([
				'status'=>0,
				'error' => config('admin.delete_error'),
				'msg' => '',
			],201);
		};
	}
	/**
	 * @name  多图上传图片批量删除方法
	 * @param id   主表id
	 * @param id   主表id
	 * @param field   字段名
	 * @return 返回处理结果
	 */
	public function del_all_images($table,$ids,$field){
		$image_id_arr = DB::table($table)->whereIn('id',$ids)->select($field)->get();
		$arr_id = [];
		foreach($image_id_arr as $v){
			if($v->$field){
				$arr_id = array_merge($arr_id,explode('|',$v->$field));
			}
		}
		if(count($arr_id) < 1){
			return $ids;
		}
		$arr_id = array_unique($arr_id);
		if($this->whereIn('id',$arr_id)->update(['status'=>0])){
			return $ids;
		}else{
			return response()->json([
				'status'=>0,
				'error' => config('admin.delete_error'),
				'msg' => '',
			],201);
		};
	}
}
