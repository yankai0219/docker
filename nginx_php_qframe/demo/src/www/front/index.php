<?php
ini_set('date.timezone', 'Asia/Shanghai');
ini_set('pdo_mysql.default_socket', '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');
//为了兼容nginx
if (isset($_SERVER['PRJ_PHP_INCLUDE_PATH']))
{
	set_include_path($_SERVER['PRJ_PHP_INCLUDE_PATH']);
}
require_once 'auto_load.php';
require_once 'hao360cn_video_utils/Loader.php';
require_once 'QFrameSmarty/Loader.php';
require_once 'firephpcore/fb.php';
spl_autoload_register('__autoload');
$var = ['a'=>'pizza', 'b'=>'cookies', 'c'=>'celery']; 
fb($var); 
fb($var, "An array"); 
fb($var, FirePHP::WARN); 
fb($var, FirePHP::INFO); 
fb($var, 'An array with an Error type', FirePHP::ERROR);
try
{
    $webApp = QFrame::createWebApp();
    $webApp->setControllerPath(QFrameConfig::getConfig('CONTROLLER_PATH'));
    $webApp->setViewPath(QFrameConfig::getConfig('VIEW_PATH'));
    $webApp->throwException(QFrameConfig::getConfig('EXCEPTION'));
    $webApp->run();
}
catch(Exception $e)
{
    var_dump($e);
    exit();
}

