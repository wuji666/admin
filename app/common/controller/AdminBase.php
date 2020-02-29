<?php
declare (strict_types=1);

namespace app\common\controller;

use app\BaseController;
use app\common\model\Menu;
use app\common\model\Role;
use think\facade\View;

class AdminBase extends BaseController
{
	/**
	 * 管理员信息
	 * @var mixed
	 */
	protected $admin;

	/**
	 * 分页数量
	 * @var int
	 */
	protected $pageSize = 15;

	public function __construct()
	{
		parent::__construct();
		$this->admin = session('admin');
		$this->viewVariable();
	}

	/**
	 * 渲染变量
	 */
	public function viewVariable()
	{
		View::assign([
			'module'     => $this->module,
			'controller' => $this->controller,
			'action'     => $this->action,
			'admin'      => $this->admin,
			'menu'       => $this->getMenu(),
		]);
	}

	/**
	 * 获取侧边栏菜单
	 * @return array|bool
	 */
	public function getMenu()
	{
		$menu = [];
		try {
			$auth_rule_list = Menu::findAll(['status' => 1], ['sort' => 'DESC', 'id' => 'ASC']);
		} catch (\Exception $e) {
			return [];
		}
		if ($this->admin['role_id'] != 0) $role_auth = explode(',', Role::findSingle(['id' => $this->admin['role_id']])['role']);
		foreach ($auth_rule_list as $value) {
			if ($this->admin['role_id'] == 0) {
				$menu[] = $value;
			} else {
				if (in_array($value['id'], $role_auth)) array_push($menu, $value);
			}
		}
		return !empty($menu) ? array2tree($menu) : [];
	}
}