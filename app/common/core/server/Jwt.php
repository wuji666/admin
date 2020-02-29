<?php

namespace app\common\core\server;

/**
 * Class Jwt
 * @package app\common\extend
 */
class Jwt
{
	/**
	 * 创建token
	 * @param string $username 用户名
	 * @param int $user_id 用户ID
	 * @param int $status 用户状态 1=>不可用，2=>可用
	 * @return string
	 */
	public function createToken($username, $user_id, $status)
	{
		$head_json = '{"type": "JWT","alg": "SHA256"}';
		$head = base64_encode($head_json);
		// payload 部分
		$payload = '{"iss": "wuji","id": ' . $user_id . ',"aud": "' . $username . '","status": ' . $status . ',"iat": ' . time() . ',"exp": ' . (time() + 15 * 24 * 60 * 60) . ',"sub": "http://buy-food.ahlzzn.com"}';
		$payload = base64_encode($payload);
		$sign = hash_hmac(strtolower((json_decode($head_json, true))['alg']), $head . $payload, config('app.salt'));
		return $head . '.' . $payload . '.' . $sign;
	}

	/**
	 * 获取客户端token
	 * @return bool|string
	 */
	public function getBearerToken()
	{
		if (!isset($_SERVER['HTTP_' . strtoupper('Authorization')])) return false; // 头部不存在 Authorization
		$auth = $_SERVER['HTTP_' . strtoupper('Authorization')];
		if (substr($auth, 0, strlen('Bearer ')) !== 'Bearer ') return false; // token 不存在
		$token = substr($auth, 7); // Bearer 字符串和空格之后的字符串，是从7开始的
		return $token;
	}

	/**
	 * 验证token
	 * @return bool
	 */
	public function decryptToken()
	{
		$token = $this->getBearerToken();
		if (!$token) return false;
		list($head, $body, $sign) = explode('.', $token);
		$head_info = json_decode(base64_decode($head), true);
		$body_info = json_decode(base64_decode($body), true);
		if ($body_info['exp'] <= time()) return false;
		$alg = $head_info['alg'];
		$hash = hash_hmac(strtolower($alg), $head . $body, config('app.salt'));
		$verify = hash_equals($sign, $hash);
		return $verify;
	}
}