<?php
declare (strict_types = 1);

namespace app\common\core\factories;


use app\common\core\interfaces\InterfaceFactory;
use app\common\core\registers\RegisterModel;
use think\App;

class FactoryModel implements InterfaceFactory
{
	/**
	 * 将所有模型实例化
	 * @return array
	 */
	public static function create()
	{
		$path = app()->getRootPath() . 'app/common/model';
		$model = [];
		foreach (scandir($path) as $v) {
			if ($v == '.' || $v == '..') {
				continue;
			}
			$class = str_replace('/', '\\', '/app/common/model/'). explode('.', $v)[0];
			$model[strtolower(explode('.', $v)[0])] = new $class;
		}
		return $model;
	}

	/**
	 * 放入容器
	 */
	public static function register()
	{
		$model = self::create();
		if (is_array($model)) {
			foreach ($model as $k => $v) {
				RegisterModel::set($k, $v);
			}
		}
	}
}