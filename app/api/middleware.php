<?php
// api中间件定义文件
return [
	// 跨域
	\think\middleware\AllowCrossDomain::class,
	// 前台用户登录
	\app\middleware\UserAuth::class,
];
