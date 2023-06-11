<?php

namespace Modules\Admin\Models;
class AuthGroup extends BaseApiModel
{

	/**
     * @name   更新时间为null时返回
     * @param  int  $value
     * @return Boolean
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value?$value:'';
    }


}
