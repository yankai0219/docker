<?php
include_once(dirname(__FILE__) . '/header.php');
class UserSvcTestCase extends UnitTestCase
{
    public function setUp()
    {
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        $logBizName = QFrameConfig::getConfig('LOG_BIZ_NAME');
        $logOpt = QFrameConfig::getConfig('LOG_OPT');
        LoaderSvc::init($db, $logPath, $logBizName, $logOpt);
    }
    public function testReg()
    {
        $nickName = 'yankai';
        $tel      = 18010066968;
        $password = 'yankai0219';
        $res = UserSvc::reg($nickName, $tel, $password);
        $this->assertEqual(true, ! empty($res));
        $result = LoaderSvc::loadDao('User')->getOne('User', array('tel' => $tel));
        $this->assertEqual($tel, $result['tel']);
    }
    public function testLogin()
    {
        $loginName = 'yankai';
        $password  = 'yankai0219';
        $res = UserSvc::login($loginName, $password);
        $this->assertEqual(true, $res);

        $loginName = '18010066968';
        $password  = 'yankai0219';
        $res = UserSvc::login($loginName, $password);
        $this->assertEqual(true, $res);

        $loginName = 'shijieguan';
        $password  = 'yankai0219';
        $res = UserSvc::login($loginName, $password);
        $this->assertEqual(false, $res);
    }
}

