<?php
/**
 * Qihoo PHP FrameWork bootstrap file(QFrame)
 * @Writen by : cc <chenchao@360.cn>
 * @http://add.corp.qihoo.net:8360/display/platform/QFrame
 */

class QFrameBase
{
    public function __construct()
    {/*{{{*/
    }/*}}}*/
    
    public static function getVersion()
    {/*{{{*/
        return 'QFrame_1.0.3';
    }/*}}}*/

    public static function createWebApp()
    {/*{{{*/
        QFrameUtil::sendSDKMsg(self::getVersion());
        return QFrameContainer::find('QFrameWeb'); 
    }/*}}}*/

}
?>
