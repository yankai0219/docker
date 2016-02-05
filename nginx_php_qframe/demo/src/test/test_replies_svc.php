
<?php
include_once(dirname(__FILE__) . '/header.php');
class RepliesSvcTestCase extends UnitTestCase
{
    public function setUp()
    {
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        LoaderSvc::init($db, $logPath);
    }
    public function testCreate()
    {
        $body      = 'replies';
        $user_id   = 1;
        $topic_id  = 2;
        $refer_id  = 3;
        $token     = 'token';
        $res = RepliesSvc::create($body, $user_id, $topic_id, $refer_id, $token);
        var_dump($res);
        $this->assertEqual(true, ! empty($res));
    }
}
