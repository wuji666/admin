<?php

namespace app\api\controller;

use app\common\controller\ApiBase;
use app\Request;
use third\onenet\OneNetApi;
use app\common\model\Device as DeviceModel;

/**
 * 设备
 * Class Device
 * @package app\api\controller
 */
class Device extends ApiBase
{
	protected $oneNet;

	public function __construct(OneNetApi $oneNet)
	{
		parent::__construct();
		$this->oneNet = $oneNet;
	}

	public function lists(Request $request)
	{
		$param = $request->get(['offset', 'length']);
		if ($param['offset'] < 0 || empty($param['length'])) $this->error(-1, '请将信息填写完整');
		$lists = DeviceModel::findLimit([], ['create_time' => 'desc'], $param['offset'], $param['length']);
		if ($lists == false) $this->error(-1, '当前暂无设备');
		$this->success(1, '', $lists);
	}

	public function detail(Request $request)
	{
		$device_id = $request->get('device_id');
		if (empty($device_id)) $this->error(-1, '请输入设备id');
		$info = $this->oneNet->device_status($device_id);
		if ($info == false) $this->error(-1, '非法请求');
		$this->success(1, '', $info);
	}


}