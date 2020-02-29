<?php
declare (strict_types = 1);

namespace app\common\core\interfaces;

use app\Request;

interface InterfaceAdminController
{
	public function index(Request $request);

	public function form(Request $request);

	public function save(Request $request);

	public function del(Request $request);
}