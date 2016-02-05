<?php
include_once(dirname(__FILE__) . '/header.php');
class PetSvcTestCase extends UnitTestCase
{
    public function setUp()
    {
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        LoaderSvc::init($db, $logPath);
    }
    public function testCreate()
    {
        $name   = 'pet';
        $age    = '20';
        $breed  = 'alasijia';
        $gender = 1;
        $weight = '20';
        $arr = PetSvc::create($name, $age, $breed, $gender, $weight);
        $this->assertEqual($name, $arr['name']);
        $this->assertEqual(true, ! empty($arr['id']));
    }
}

