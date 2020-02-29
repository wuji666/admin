<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\core\interfaces\InterfaceAdminController;
use app\Request;

class Index extends AdminBase implements InterfaceAdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index(Request $request)
	{
		return view();
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