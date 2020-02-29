<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\core\interfaces\InterfaceAdminController;
use app\Request;
use think\cache\driver\Redis;

class System extends AdminBase implements InterfaceAdminController
{
	public function clear()
	{
		$res = delete_dir_file(app('app')->getRuntimePath() . 'cache/') || delete_dir_file(app('app')->getRuntimePath() . 'temp/');
		if ($res)
			return json(['code' => 1, 'msg' => '清除缓存成功']);
		else
			return json(['code' => -1, 'msg' => '清除缓存失败']);
	}

	public function index(Request $request)
	{
		// TODO: Implement index() method.
	}

	public function form(Request $request)
	{
		// TODO: Implement form() method.
	}

	public function save(Request $request)
	{
		// TODO: Implement save() method.
	}

	public function del(Request $request)
	{
		// TODO: Implement del() method.
	}
}