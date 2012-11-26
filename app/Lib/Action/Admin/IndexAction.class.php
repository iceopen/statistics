<?php
class IndexAction extends Action {
	public function index(){
		$this->display();
	}

	public function items(){
		$AgentModel = M('Agent');
		$count      = $AgentModel->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$items = $AgentModel->order('create_time DESC')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();
		$this->assign('items',$items);
		$this->assign('page',$show);
		$this->display();
	}
}
?>