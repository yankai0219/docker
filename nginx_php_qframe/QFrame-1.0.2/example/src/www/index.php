<?php
require_once 'QFrame/Loader.php';
require_once 'auto_load.php';
$webApp = QFrame::createWebApp();
$webApp->throwException(QFrameConfig::getConfig('EXCEPTION'));
//�Զ���ControllerPath
//$webApp->setControllerPath('/home/chenchao/project/fw/example/src/application/controllers/');
//$webApp->setViewPath('/home/chenchao/project/test/src/application/testview/scripts/');

/*
 * �Զ���·�ɹ���
 *
  $userroute = new QFrameStandRoute(
       'u/:qid',
       array(
              'controller' => 'my',
              'action'     => 'index',
       )
);
QFrameContainer::find('QFrameRouter')->addRoute('user',$userroute);
 */
$webApp->run();
?>
