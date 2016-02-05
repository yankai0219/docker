<?php
include_once(dirname(__FILE__) . '/header.php');
class TopicsSvcTestCase extends UnitTestCase
{
    public function setUp()
    {
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        $logBizName = QFrameConfig::getConfig('LOG_BIZ_NAME');
        $logOpt = QFrameConfig::getConfig('LOG_OPT');
        LoaderSvc::init($db, $logPath, $logBizName, $logOpt);
    }
    public function testCreate()
    {
        $title          = 'title';
        $body           = 'body';
        $replies_count  = 1; 
        $user_id        = 3; 
        $token          = 'token';
        $res = TopicsSvc::create($title, $body, $replies_count, $user_id, $token);
        var_dump($res);
        $this->assertEqual(true, ! empty($res));
    }
}
