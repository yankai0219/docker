
<?php
include_once(dirname(__FILE__) . '/header.php');
class PicturesSvcTestCase extends UnitTestCase
{
    public function setUp()
    {
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        LoaderSvc::init($db, $logPath);
    }
    public function testCreate()
    {
        $name           = 'pic1';
        $imageable_type = 'imageable_type';
        $token          = 'token';
        $res = PicturesSvc::create($name, $imageable_type, $token);
        var_dump($res);
        $this->assertEqual(true, ! empty($res));
    }
}
