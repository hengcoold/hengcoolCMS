<?php

namespace Modules\Admin\Models;
class AuthConfig extends BaseApiModel
{
    /**
	 * @name 关联logo图片
	 * @description
	 * @author hengcool
	 * @date 2021/6/21 15:04
	 * @return JSON
	 **/
    public function logo_one()
    {
        return $this->hasOne('Modules\Admin\Models\AuthImage','id','logo_id');
    }

}
