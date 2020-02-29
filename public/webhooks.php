<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/23 0023
 * Time: 13:41
 */
$post=file_get_contents('php://input');//获取请求携带的参数

$post=json_decode($post ,true);//将json转换成数组

// 验证密码 保证请求安全性
if($post['password'] == 'wuji19980818'){
	exec('git pull origin master 2<&1', $output);
	echo json_encode($output);
}else{
	echo '失败';
}
