<?php
declare (strict_types=1);

namespace app;

use app\common\core\factories\FactoryModel;
use app\common\core\registers\RegisterModel;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\response\Json;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
	/**
	 * 是否批量验证
	 * @var bool
	 */
	protected $batchValidate = false;

	/**
	 * 控制器中间件
	 * @var array
	 */
	protected $middleware = [];

	/**
	 * 模型
	 * @var array
	 */
	protected $model;

	/**
	 * 模块
	 * @var string
	 */
	protected $module;

	/**
	 * 控制器
	 * @var string
	 */
	protected $controller;

	/**
	 * 方法
	 * @var string
	 */
	protected $action;

	/**
	 * 构造方法
	 * @access public
	 * BaseController constructor.
	 */
	public function __construct()
	{
		FactoryModel::register();
		$this->model = RegisterModel::getAll();
		$this->module = strtolower(trim(request()->rootUrl(), '/'));
		$this->controller = strtolower(request()->controller());
		$this->action = strtolower(request()->action());
	}

	/**
	 * 验证数据
	 * @access protected
	 * @param array $data 数据
	 * @param string|array $validate 验证器名或者验证规则数组
	 * @param array $message 提示信息
	 * @param bool $batch 是否批量验证
	 * @return array|string|true
	 * @throws ValidateException
	 */
	protected function validate(array $data, $validate, array $message = [], bool $batch = false)
	{
		if (is_array($validate)) {
			$v = new Validate();
			$v->rule($validate);
		} else {
			if (strpos($validate, '.')) {
				// 支持场景
				[$validate, $scene] = explode('.', $validate);
			}
			$class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
			$v = new $class();
			if (!empty($scene)) {
				$v->scene($scene);
			}
		}

		$v->message($message);

		// 是否批量验证
		if ($batch || $this->batchValidate) {
			$v->batch(true);
		}

		return $v->failException(true)->check($data);
	}

	/**
	 * 返回错误数据
	 * @param int $code
	 * @param string $msg
	 * @param int $http_code
	 */
	public function error(int $code, string $msg, int $http_code = 200)
	{
		throw new HttpResponseException(json(['code' => $code, 'msg' => $msg], $http_code));
	}

	/**
	 * 返回成功数据
	 * @param int $code
	 * @param string $msg
	 * @param array $data
	 * @param int $http_code
	 */
	public function success(int $code, string $msg, array $data = [], int $http_code = 200)
	{
		throw new HttpResponseException(json(['code' => $code, 'msg' => $msg, 'data' => $data], $http_code));
	}
}
