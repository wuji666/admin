<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\core\interfaces\InterfaceAdminController;
use app\common\model\Admin;
use app\Request;
use think\facade\Session;

class Login extends AdminBase implements InterfaceAdminController
{

	/**
	 * 登录
	 * @param Request $request
	 * @return \think\response\Json|\think\response\View
	 */
	public function login(Request $request)
	{
		if ($request->isAjax()) {
			$param = $request->param();
			if (empty($param['username']) || empty($param['password']) || empty($param['verify'])) return json(['code' => -1, 'msg' => '请将信息填写完整']);
			if (!captcha_check($param['verify'])) return json(['code' => -1, 'msg' => '验证码填写错误']);
			try {
				$res = Admin::findSingle(['username' => $param['username']]);
				if (empty($res)) return json(['code' => -1, 'msg' => '账号或密码填写错误']);
				$password = md5($param['password'] . $res['salt']);
				if ($password != $res['password']) return json(['code' => -1, 'msg' => '账号或密码填写错误']);
				Session::set('admin', [
					'id'       => $res['id'],
					'username' => $res['username'],
					'role_id'  => $res['role_id']
				]);
				return json(['code' => 1, 'msg' => '登录成功']);
			} catch (\Exception $e) {
				return json(['code' => -2, 'msg' => $e->getMessage()]);
			}
		}
		if (!empty(session('admin'))) return redirect(config('app.host') . '/admin/index/index');
		return view('login/login');
	}

	/**
	 * 退出登录
	 * @return \think\response\Redirect
	 */
	public function loginOut()
	{
		Session::clear('admin');
		return redirect(config('app.host') . '/admin/login/login');
	}

	/**
	 * 验证码
	 * @param Request $request
	 * @return \think\Response
	 */
	public function captcha(Request $request)
	{
		$id = $request->param('id');
		return captcha($id);
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