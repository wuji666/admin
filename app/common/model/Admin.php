<?php
declare (strict_types = 1);

namespace app\common\model;

use app\BaseModel;

class Admin extends BaseModel
{
	public function role()
	{
		return $this->belongsTo(Role::class);
	}
}