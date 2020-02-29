<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\core\interfaces\InterfaceAdminController;
use app\common\model\Admin as AdminModel;
use app\common\model\Role;
use app\Request;

class Admin extends AdminBase implements InterfaceAdminController
{
	public function index(Request $request)
	{
		$where = [
			['a.role_id', '<>', 0]
		];
		$lists = AdminModel::alias('a')->leftJoin('role b', 'a.role_id=b.id')->where($where)->field('a.*,b.name')->paginate($this->pageSize);
		return view('admin/index', [
			'lists' => $lists
		]);
	}

	public function form(Request $request)
	{
		$id = $request->param('id');
		$info = [];
		if (!empty($id)) $info = AdminModel::findSingle(['id' => $id]);
		$role_lists = Role::findAll();
		return view('admin/form', [
			'info'       => $info,
			'role_lists' => $role_lists
		]);
	}

	public function save(Request $request)
	{
		$param = $request->param();
		$id = $param['id'];
		unset($param['id']);
		$where = [];
		if (!empty($id)) {
			$where = ['id' => $id];
			if (empty($param['username']) || empty($param['role_id'])) $this->error(-1, '请将信息填写完整');
			if (!empty($param['password'])) {
				$param['salt'] = get_random_str();
				$param['password'] = md5($param['password'] . $param['salt']);
			} else {
				unset($param['password']);
			}
		} else {
			if (empty($param['username']) || empty($param['password']) || empty($param['role_id'])) $this->error(-1, '请将信息填写完整');
			$param['salt'] = get_random_str();
			$param['password'] = md5($param['password'] . $param['salt']);
		}
		$res = AdminModel::saveData($param, $where);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '操作成功');
	}

	public function del(Request $request)
	{
		$id = $request->param('id');
		if (empty($id)) $this->error(-1, '非法请求');
		$res = AdminModel::del(['id' => $id]);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '操作成功');
	}
}