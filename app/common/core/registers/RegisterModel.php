<?php
declare (strict_types = 1);

namespace app\common\core\registers;


use app\common\core\interfaces\InterfaceRegister;

class RegisterModel implements InterfaceRegister
{
	protected static $tree = [];

	public static function set($alias, $object)
	{
		self::$tree[$alias] = $object;
	}

	public static function get($alias)
	{
		return self::$tree[$alias];
	}

	public static function getAll()
	{
		return self::$tree;
	}

	public static function _unset($alias)
	{
		unset(self::$tree[$alias]);
	}
}