<?php
declare (strict_types=1);

namespace app\middleware;

use app\common\controller\AdminBase;
use app\common\controller\ApiBase;
use app\common\core\server\Jwt;
use app\common\model\Menu;
use app\common\model\Role;

class UserAuth extends ApiBase
{
	protected $except = [
		'api/login/login',
	];

	/**
	 * @param $request
	 * @param \Closure $next
	 * @return mixed|\think\response\Redirect
	 */
	public function handle($request, \Closure $next)
	{
		$path = ltrim($request->root() . '/' . explode('.', $request->pathinfo())[0], '/');
		if (!in_array($path, $this->except) && user() == false) $this->error(-3, '请先登录');
		return $next($request);
	}
}
