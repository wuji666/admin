<?php
// admin中间件定义文件
return [
	// Session初始化
	\think\middleware\SessionInit::class,
	// 后台用户登录
	\app\middleware\AdminAuth::class,
];
