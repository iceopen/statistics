<?php
class IndexAction extends Action {
	public function index(){
		//[HTTP_USER_AGENT] 请求用户信息
		//[REDIRECT_URL] 请求地址
		//[REQUEST_TIME] 请求时间
		$data = array();
		$data['agent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['url'] = $_SERVER['REDIRECT_URL'];
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['session_id'] = session_id();
		$data['y'] = date('Y');
		$data['m'] = date('n');
		$data['d'] = date('j');
		$data['ip'] = get_client_ip();
		M('Agent')->add($data);
	}
}