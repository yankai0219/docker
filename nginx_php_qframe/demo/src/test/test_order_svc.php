<?php
include_once(dirname(__FILE__) . '/header.php');
class OrderSvcTestCase extends UnitTestCase
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
        $oi = array(
            'user_id'          => 2, 
            'user_name'        => 'user_name',
            'user_tel'         => 'user_tel',
            'user_email'       => 'user_email',
            'user_addr'        => 'user_addr',
            'pet_name'         => 'pet_name',
            'pet_age'          => 2,
            'pet_breed'        => 'pet_breed',
            'pet_gender'       => 'pet_gender',
            'pet_weight'       => 22,
            'begindate'        => 'begindate',
            'enddate'          => 'enddate',
            'type'             => 22,
            'user_spec_info'   => 'user_spec_info',
            'pet_spec_info'    => 'pet_spec_info',
            'pet_id'           => 1,
            'begindate'        => '2015-02-12 22:22',
            'enddate'          => '2015-02-13 22:22',
            );
        $res = OrdersSvc::create($oi);
        $this->assertEqual(2, $res['user_id']);
    }
}

