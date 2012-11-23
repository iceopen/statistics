<?php
class IndexAction extends Action {
	public function index(){
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
}
?>