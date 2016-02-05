<?php
include_once(dirname(__FILE__) . '/header.php');
class IdentifycodeSvcTestCase extends UnitTestCase
{
    public function setUp()
    {
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        $logBizName = QFrameConfig::getConfig('LOG_BIZ_NAME');
        $logOpt = QFrameConfig::getConfig('LOG_OPT');
        LoaderSvc::init($db, $logPath, $logBizName, $logOpt);
        ErrDef::$errno = 0; // restore to zero
    }
    public function testSend()
    {
        $tel = 18010066967;
        $res = IdentifycodeSvc::send($tel);
        echo __LINE__ . 'errno:' . var_export(ErrDef::$errno, true) . "\n";
        $this->assertEqual(true, $res);
    }
    public function testCheckFailed()
    {
        $tel = 18010066967;
        $res = IdentifycodeSvc::check($tel, 1111);
        $this->assertEqual(false, $res);
        echo __LINE__ . 'errno:' . var_export(ErrDef::$errno, true) . "\n";
    }
    public function testCheckSucceed()
    {
        $tel = 18010066967;
        $oneRow = IdentifycodeSvc::getOneByTel($tel);
        $res = IdentifycodeSvc::check($tel, $oneRow['identifycode']);
        echo  'ic:' . var_export($oneRow['identifycode'], true) . "\n";
        $this->assertEqual(true, $res);
        echo __LINE__ . 'errno:' . var_export(ErrDef::$errno, true) . "\n";
    }
}
