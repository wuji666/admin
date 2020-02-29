<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\BaseModel;
use app\common\controller\AdminBase;
use app\common\core\interfaces\InterfaceAdminController;
use app\Request;
use app\common\model\Role as RoleModel;
use app\common\model\Menu;

class Role extends AdminBase implements InterfaceAdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index(Request $request)
	{
		$p_id = $request->param('p_id', 0);
		$lists = RoleModel::paginate($this->pageSize);
		return view('role/index', [
			'lists' => $lists,
			'p_id'  => $p_id
		]);
	}

	public function form(Request $request)
	{
		$id = $request->param('id');
		$info = [];
		$auth = [];
		if (!empty($id)) {
			$info = RoleModel::findSingle(['id' => $id]);
			$auth = explode(',', $info['role']);
		}
		$menu_lists = Menu::findAll();
		return view('role/form', [
			'info'       => $info,
			'auth'       => $auth,
			'menu_lists' => array2tree($menu_lists),
		]);
	}

	public function save(Request $request)
	{
		$param = $request->param();
		$id = $param['id'];
		unset($param['id']);
		$where = [];
		if (!empty($id)) $where = ['id' => $id];
		$param['role'] = implode(',', $param['role']);
		$res = RoleModel::saveData($param, $where);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '操作成功');
	}

	public function del(Request $request)
	{
		$id = $request->param('id');
		if (empty($id)) $this->error(-1, '非法请求');
		$res = RoleModel::del(['id' => $id]);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '操作成功');
	}
}
