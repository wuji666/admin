<?php
// 应用公共文件

if (!function_exists('is_mobile')) {
	/**
	 * 判断是否为手机访问
	 * @return  boolean
	 */
	function is_mobile()
	{
		static $is_mobile;

		if (isset($is_mobile)) {
			return $is_mobile;
		}

		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			$is_mobile = false;
		} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
		) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}

		return $is_mobile;
	}
}
if (!function_exists('check_mobile_number')) {
	/**
	 * 手机号格式检查
	 * @param string $mobile
	 * @return bool
	 */
	function check_mobile_number($mobile)
	{
		if (!is_numeric($mobile)) {
			return false;
		}
		$reg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';

		return preg_match($reg, $mobile) ? true : false;
	}
}

if (!function_exists('get_random_str')) {
	/**
	 * 生成随机字符串
	 * @return bool|string
	 */
	function get_random_str()
	{
		return substr(str_shuffle(config('app.salt')), mt_rand(0, strlen(config('app.salt')) - 11), 10);
	}
}

if (!function_exists('delete_dir_file')) {
	/**
	 * 循环删除目录和文件
	 * @param string $dir_name
	 * @return bool
	 */
	function delete_dir_file($dir_name)
	{
		$result = false;
		if (is_dir($dir_name)) {
			if ($handle = opendir($dir_name)) {
				while (false !== ($item = readdir($handle))) {
					if ($item != '.' && $item != '..') {
						if (is_dir($dir_name . DIRECTORY_SEPARATOR . $item)) {
							delete_dir_file($dir_name . DIRECTORY_SEPARATOR . $item);
						} else {
							unlink($dir_name . DIRECTORY_SEPARATOR . $item);
						}
					}
				}
				closedir($handle);
				if (rmdir($dir_name)) {
					$result = true;
				}
			}
		}
		return $result;
	}
}

if (!function_exists('array2tree')) {
	/**
	 * 构建层级（树状）数组
	 * @param array $array 要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
	 * @param string $p_id_name 父级ID的字段名
	 * @param string $child_key_name 子元素键名
	 * @return array|bool
	 */
	function array2tree(&$array, $p_id_name = 'p_id', $child_key_name = 'children')
	{
		$counter = array_children_count($array, $p_id_name);
		if (!isset($counter[0]) || $counter[0] == 0) {
			return $array;
		}
		$tree = [];
		while (isset($counter[0]) && $counter[0] > 0) {
			$temp = array_shift($array);
			if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
				array_push($array, $temp);
			} else {
				if ($temp[$p_id_name] == 0) {
					$tree[] = $temp;
				} else {
					$array = array_child_append($array, $temp[$p_id_name], $temp, $child_key_name);
				}
			}
			$counter = array_children_count($array, $p_id_name);
		}
		return $tree;
	}
}

if (!function_exists('array_children_count')) {
	/**
	 * 子元素计数器
	 * @param array $array
	 * @param int $p_id
	 * @return array
	 */
	function array_children_count($array, $p_id)
	{
		$counter = [];
		foreach ($array as $item) {
			$count = isset($counter[$item[$p_id]]) ? $counter[$item[$p_id]] : 0;
			$count++;
			$counter[$item[$p_id]] = $count;
		}
		return $counter;
	}
}

if (!function_exists('array_child_append')) {
	/**
	 * 把元素插入到对应的父元素$child_key_name字段
	 * @param        $parent
	 * @param        $p_id
	 * @param        $child
	 * @param string $child_key_name 子元素键名
	 * @return mixed
	 */
	function array_child_append($parent, $p_id, $child, $child_key_name)
	{
		foreach ($parent as &$item) {
			if ($item['id'] == $p_id) {
				if (!isset($item[$child_key_name])) $item[$child_key_name] = [];
				$item[$child_key_name][] = $child;
			}
		}
		return $parent;
	}
}

if (!function_exists('array2level')) {
	/**
	 * 数组层级缩进转换
	 * @param array $array 源数组
	 * @param int $p_id
	 * @param int $level
	 * @return array
	 */
	function array2level($array, $p_id = 0, $level = 1)
	{
		static $list = [];
		foreach ($array as $v) {
			if ($v['p_id'] == $p_id) {
				$v['level'] = $level;
				$list[] = $v;
				array2level($array, $v['id'], $level + 1);
			}
		}
		return $list;
	}
}

if (!function_exists('check_auth')) {
	/**
	 * 检查权限
	 * @param string $path 当前菜单路径
	 * @return bool
	 */
	function check_auth(string $path = '')
	{
		if (empty($path)) return false;
		$admin = session('admin');
		if ($admin['role_id'] == 0) return true;
		$menu = \app\common\model\Menu::findSingle(['name' => $path]);
		if (is_string($menu) || empty($menu)) return false;
		$role = explode(',', \app\common\model\Role::findSingle(['id' => $admin['role_id']])['role']);
		if (!in_array($menu['id'], $role)) return false;
		return true;
	}
}

if (!function_exists('hex_to_str')) {
	/**
	 * 转换二进制
	 * @param $hex
	 * @return string
	 */
	function hex_to_str($hex)
	{
		$str = "";
		for ($i = 0; $i < strlen($hex) - 1; $i += 2)
			$str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
		return $str;
	}
}

if (!function_exists('user')) {
	/**
	 * 获取用户信息
	 * @return bool|object
	 */
	function user()
	{
		$jwt = new \app\common\core\server\Jwt();
		$token = $jwt->getBearerToken();
		if (!$token) return false;
		$body_info = json_decode(base64_decode(explode('.', $token)[1]), true);
		if ($body_info['exp'] <= time()) return false;
		$res = $jwt->decryptToken();
		$user = [];
		if (!$res) return false;
		$user = \app\common\model\User::findSingle(['id' => $body_info['id']]);
		return (object)$user;
	}
}

if (!function_exists('str_to_location')) {
	/**
	 * 字符串转经纬度
	 * @param string $str 地址
	 * @return array|bool
	 */
	function str_to_location($str)
	{
		if (empty($str)) return false;
		$url = 'http://api.map.baidu.com/geocoding/v3/?address=' . $str . '&output=json&ak=' . config('third.baidu_map.ak');
		$res = json_decode(curl_request($url));
		if ($res->status == 1) return false;
		$data = [
			'lon' => mb_convert_encoding($res->result->location->lng, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5'),
			'lat' => mb_convert_encoding($res->result->location->lat, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5'),
		];
		return $data;
	}
}

if (!function_exists('curl_request')) {
	/**
	 * curl请求
	 * @param $url
	 * @param string $method
	 * @param null $data
	 * @return mixed
	 */
	function curl_request($url, $method = 'GET', $data = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}



