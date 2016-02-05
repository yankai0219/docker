<?php
//为了兼容nginx
if (isset($_SERVER['PRJ_PHP_INCLUDE_PATH']))           
{
	set_include_path($_SERVER['PRJ_PHP_INCLUDE_PATH']);
}

require_once 'QFrame/Loader.php';
require_once 'auto_load.php';
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
    //var_dump($e );
    //exit();
}

