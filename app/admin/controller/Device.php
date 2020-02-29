<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\core\interfaces\InterfaceAdminController;
use app\common\model\Device as DeviceModel;
use app\Request;
use third\onenet\OneNetApi;
use app\common\model\User;

class Device extends AdminBase implements InterfaceAdminController
{
	protected $oneNet;

	public function __construct(OneNetApi $oneNetApi)
	{
		parent::__construct();
		$this->oneNet = $oneNetApi;
	}

	public function index(Request $request)
	{
		$lists = DeviceModel::paginate($this->pageSize);
		return view('device/index', [
			'lists' => $lists,
			'user'  => User::findAll(['status' => 2]),
		]);
	}

	public function form(Request $request)
	{
		$id = $request->param('id');
		$info = [];
		if (!empty($id)) $info = DeviceModel::findSingle(['id' => $id]);
		return view('device/form', [
			'info' => $info,
		]);
	}

	public function save(Request $request)
	{
		$param = $request->param();
		$id = $param['id'];
		unset($param['id']);
		$where = [];
		$data = $param;
		$data['location'] = str_to_location($data['location']) ? (object)str_to_location($data['location']) : $this->error(-1, '地址不合法');
		$data['other'] = (object)explode(',', $data['other']);
		$data['tags'] = explode(',', $data['tags']);
		if (!empty($id)) {
			$where = ['id' => $id];
			$device_id = $data['device_id'];
			unset($data['device_id']);
			unset($data['protocol']);
			$device_id = $this->oneNet->device_edit($device_id, $data);
		} else {
			$device_id = $this->oneNet->device_add($data);
		}
		if ($device_id == false) $this->error(-1, '操作失败');
		if (empty($id)) $param['device_id'] = $device_id['device_id'];
		$res = DeviceModel::saveData($param, $where);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '操作成功');
	}

	public function del(Request $request)
	{
		$id = $request->param('id');
		if (empty($id)) $this->error(-1, '非法请求');
		$one_net_res = $this->oneNet->device_delete($id);
		if ($one_net_res == false) $this->error(-1, '操作失败');
		$res = DeviceModel::del(['device_id' => $id]);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '操作成功');
	}

	/**
	 * 刷新设备状态
	 * @param Request $request
	 */
	public function reload(Request $request)
	{
		$param = $request->param('device_id');
		if (empty($param)) $this->error(-1, '非法请求');
		$res = $this->oneNet->device_status($param);
		if ($res == false) $this->error(-1, '系统异常');
		foreach ($res['devices'] as $v) {
			$online = $v['online'] ? 2 : 1;
			$res = DeviceModel::saveData(['online' => $online], ['device_id' => $v['id']]);
			if (is_string($res)) $this->error(-1, $v['id'] . '-更新失败');
		}
		$this->success(1, '更新成功');
	}

	/**
	 * 分配设备
	 * @param Request $request
	 */
	public function distribution(Request $request)
	{
		$param = $request->post();
		if (empty($param['user_id']) || empty($param['id'])) $this->error(-1, '请选择用户');
		$res = DeviceModel::saveData(['user_id' => $param['user_id']], ['id' => $param['id']]);
		if (is_string($res)) $this->error(-1, $res);
		$this->success(1, '分配成功');
	}
}
