<?php
ini_set('display_errors', 0);
$PRJ_ROOT = dirname(dirname(dirname(__FILE__)));
ini_set('include_path', $PRJ_ROOT . '/config/' . ':' . get_include_path());
require_once($PRJ_ROOT . '/src/service/test_svc.php');

$test_svc= new TestService();
$data = $test_svc->make();
$endTime = microtime(true);
$logInfo['errno'] = 0; 
$logInfo['errormsg'] = $data; 
$output = json_encode($logInfo);
error_log($output . "\n", 3, $PRJ_ROOT . '/logs/yk_process.log');
echo $output;
