<?php
class IndexAction extends Action {
	public function index(){
	}
	public function getInfo(){
		$data = array();
		$data['agent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['url'] = $_SERVER['HTTP_REFERER'];
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['session_id'] = session_id();
		$data['y'] = date('Y');
		$data['m'] = date('n');
		$data['d'] = date('j');
		$data['ip'] = get_client_ip();

		M('Agent')->add($data);
	}

	/**
	 * 获取IP地址信息
	 *@author Ice <iceinto@mallog.com.cn>
	 */
	public function ipinfo(){
		$ip = trim($this->_get('ip'));
		$nowTime = time();
		//查询是否存在相应IP地址
		$ipModel = M('Ip');
		$map = array();
		$map['ip'] = $ip;
		$ipDbInfo = $ipModel->where($map)->find();
		if($ipDbInfo){
			echo $ipDbInfo['info'];
		}else{
			$url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
			$ipInfo = file_get_contents($url);
			$data = array();
			$data['update_time'] = time();
			$data['info'] = $ipInfo;
			$data['ip'] = $ip;
			$ipModel->add($data);
			echo $ipInfo;
		}
	}
}
?>