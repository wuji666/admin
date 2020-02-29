<?php
declare (strict_types=1);

namespace app\common\model;

use app\BaseModel;

class Menu extends BaseModel
{
	public static function getAllSon($p_id, $hook = false)
	{
		$data = self::where('p_id', 'in', $p_id)->field('id')->select()->toArray();
		if ($hook == false) $id = [];
		static $id = [];
		if (empty($data)) return $id;
		foreach ($data as $v) {
			$id[] = $v['id'];
		}
		return self::getAllSon($id, true);
	}
}