<?php
declare (strict_types=1);

namespace app\middleware;

use app\common\controller\AdminBase;
use app\common\model\Menu;
use app\common\model\Role;

class AdminAuth extends AdminBase
{
	protected $except = [
		'admin/login/login',
		'admin/login/captcha',
		'admin/system/clear',
	];

	/**
	 * @param $request
	 * @param \Closure $next
	 * @return mixed|\think\response\Redirect
	 */
	public function handle($request, \Closure $next)
	{
		$path = ltrim($request->root() . '/' . explode('.', $request->pathinfo())[0], '/');
		$admin = session('admin');
		if (!in_array($path, $this->except) && empty($admin)) return redirect(config('app.host') . '/admin/login/login');
		if (!empty($admin)) {
			$menu_id = Menu::findSingle(['name' => $path]);
			if ($admin['role_id'] != 0) $role_lists = explode(',', Role::findSingle(['id' => $admin['role_id']])['role']);
			if (!in_array($path, $this->except) && $admin['role_id'] != 0 && !in_array($menu_id['id'], $role_lists)) $this->error(-3, '暂无权限');
		}
		return $next($request);
	}
}
