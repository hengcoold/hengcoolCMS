<?php
namespace Modules\Admin\Models;
use DateTimeInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AuthAdmin extends Authenticatable implements JWTSubject
{
    use Notifiable;
	protected $guard = 'auth_admin';
	protected $hidden = [
		'password'
	];
	/**
	 * @name jwt标识
	 * @description
	 * @author hengcool
	 * @date 2021/6/12 3:11
	 **/
	public function getJWTIdentifier()
    {
        return $this->getKey();
    }
	/**
	 * @name jwt自定义声明
	 * @description
	 * @author hengcool
	 * @date 2021/6/12 3:11
	 **/
    public function getJWTCustomClaims()
    {
        return [];
    }
	/**
	 * @name 更新时间为null时返回
	 * @description
	 * @author hengcool
	 * @date 2021/6/12 3:11
	 **/
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }
	/**
	 * @name  关联权限组表   多对一
	 * @description
	 * @author hengcool
	 * @date 2021/6/12 3:12
	 **/
	 public function auth_groups()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthGroup','group_id','id');
    }
    /**
     * @name  关联平台项目表   多对一
     * @description
     * @author hengcool
     * @date 2021/6/12 3:12
     **/
    public function auth_projects()
    {
        return $this->belongsTo('Modules\Admin\Models\AuthProject','project_id','id');
    }
    /**
     * @name 时间格式传唤
     * @description
     * @author hengcool
     * @date 2021/6/17 16:15
     **/
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
