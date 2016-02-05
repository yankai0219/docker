<?php
class LogSvc 
{
    static private $_bizName;
    static public function init($logPath, $bizName, $logOpt)
    {
        if (empty($logPath))
        {
            echo '日志路径没有设置';
            exit;
        }
        self::$_bizName = $bizName;
        VUtilsLog::reg(self::$_bizName, $logPath, $logOpt);
        VUtilsLog::setLogId(self::getLogId());
        VUtilsLog::setBizName(self::$_bizName);
    }
    static public function getLogId()
    {
        static $logId = false;
        if ($logId === false)
        {
            $logId = md5(time());
        }
        return $logId;
    }
    static public function error($msg)
    {
        self::_getVutilsLog()->error($msg);
    }
    static public function warning($msg)
    {
        self::_getVutilsLog()->warning($msg);
    }
    static public function biz($msg)
    {
        self::_getVutilsLog()->biz($msg);
    }
    static public function debug($msg)
    {
        self::_getVutilsLog()->debug($msg);
    }
    static public function sql($msg)
    {
        self::_getVutilsLog()->sql($msg);
    }
    static private function _getVutilsLog()
    {
        static $logObj = false;
        if ($logObj === false)
        {
           $logObj = VUtilsLog::ins(self::$_bizName);
        }
        return $logObj;
    }
}
