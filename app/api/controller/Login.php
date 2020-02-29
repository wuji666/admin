<?php

namespace app\api\controller;

use app\common\controller\ApiBase;
use app\common\core\server\Jwt;
use app\common\model\User;
use app\Request;

/**
 * 登录
 * Class Login
 * @package app\api\controller
 */
class Login extends ApiBase
{
	public function login(Request $request, Jwt $jwt)
	{
		$post = $request->post();
		if (empty($post['username']) || empty($post['password'])) $this->error(-1, '请将用户信息填写完整');
		$res = User::findSingle(['username' => $post['username']]);
		if (is_string($res) || empty($res)) $this->error(-1, '请输入正确的用户名和密码');
		if ($res['status'] != 2) $this->error(-1, '当前用户不可用');
		$token = $jwt->createToken($res['username'], $res['id'], $res['status']);
		if (empty($token)) $this->error(-1, '登录失败');
		$data = ['token' => $token, 'user_id' => $res['id'], 'username' => $res['username']];
		$this->success(1, '登录成功', $data);
	}
}
