<?php
class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
        $this->setNoViewRender(true);
        $data['num'] = UserSvc::getCntAllUser() + IndexDef::FAKE_INC_NUM;
        $data['spec'] = array(
            'name'   => IndexDef::NAME,
            'addr'   => IndexDef::ADDR,
            'image'  => IndexDef::IMAGE,
            'remark' => IndexDef::REMARK,
        );
        $data['errno'] = 0;
        $data['error'] = '';
        echo json_encode($data);
    }
}
