<?php
class IndexAction extends Action
{
    public function index()
    {
        $this->display();
    }

    public function items()
    {
        $AgentModel = M('Agent');
        $count = $AgentModel->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $items = $AgentModel->order('create_time DESC')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();
        import("@.ORG.UserAgentParser");
        //var_dump(UserAgentParser::getBrowser($_SERVER['HTTP_USER_AGENT']));
        //var_dump(UserAgentParser::getOperatingSystem($_SERVER['HTTP_USER_AGENT']));
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $browser = UserAgentParser::getBrowser($item['agent']);
                $system = UserAgentParser::getOperatingSystem($item['agent']);
                $items[$key]['browser'] = $browser['name'] .' '. $browser['version'];
                $items[$key]['system'] = $system['name'] ;
            }
        }
        $this->assign('items', $items);
        $this->assign('page', $show);
        $this->display();
    }
}

?>