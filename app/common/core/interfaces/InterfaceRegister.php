<?php
declare (strict_types=1);

namespace app\common\core\interfaces;


interface InterfaceRegister
{
	public static function set($alias, $object);

	public static function get($alias);

	public static function getAll();

	public static function _unset($alias);
}