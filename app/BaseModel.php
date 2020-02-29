<?php
declare (strict_types=1);

namespace app;

use app\common\core\interfaces\InterfaceModel;
use think\Model;
use app\ExceptionHandle;

abstract class BaseModel extends Model implements InterfaceModel
{
	/**
	 * 查找单条数据
	 * @param array $where
	 * @return array|string
	 */
	public static function findSingle(array $where = [])
	{
		try {
			$res = self::where($where)->find();
			return empty($res) ? [] : $res->toArray();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * 查找全部数据
	 * @param array $where
	 * @param array $order
	 * @return array|string
	 */
	public static function findAll(array $where = [], array $order = ['create_time' => 'desc'])
	{
		try {
			$res = self::where($where)->order($order)->select();
			return empty($res) ? [] : $res->toArray();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * 查找部分数据
	 * @param array $where
	 * @param array $order
	 * @param int $offset
	 * @param int $length
	 * @return array|string
	 */
	public static function findLimit(array $where = [], array $order = ['create_time' => 'desc'], int $offset = 0, int $length = 10)
	{
		try {
			$res = self::where($where)->order($order)->limit($offset, $length)->select();
			return empty($res) ? [] : $res->toArray();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * 新增和更新
	 * @param array $data
	 * @param array $where
	 * @return array|string
	 */
	public static function saveData(array $data = [], array $where = [])
	{
		try {
			if (empty($where))
				return self::create($data)->toArray();
			else
				return self::update($data, $where)->toArray();
		} catch (\Exception $e) {
			return $e->getMessage();
		}

	}

	/**
	 * 删除
	 * @param array $where
	 * @return bool|string
	 */
	public static function del(array $where = [])
	{
		try {
			return self::where($where)->delete();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}
}