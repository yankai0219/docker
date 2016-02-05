<?php
class BaseController extends QFrameAction
{/*{{{*/
    public function __construct()
    {/*{{{*/
        $db      = QFrameConfig::getConfig('DB_CONF');
        $logPath = QFrameConfig::getConfig('LOG_PATH');
        $logBizName = QFrameConfig::getConfig('LOG_BIZ_NAME');
        $logOpt = QFrameConfig::getConfig('LOG_OPT');
        LoaderSvc::init($db, $logPath, $logBizName, $logOpt);
        parent::__construct();
    }/*}}}*/
    public function output($errno, $data = array())
    {
        if ($errno != ErrDef::VALID_DATA)
        {
            $tmp['error'] = ErrDef::getErrMsg($errno);
        }
        $tmp['errno'] = $errno;
        $tmp['result'] = $data;
        return $tmp;
    }
    protected function ajaxOutput($errno, $error = null, $data = array())
    {
        if ($errno != ErrDef::VALID_DATA)
        {
            $error = ($error) ? $error : ErrDef::getErrMsg($errno);
        }
        $res = array(
            'errno' => $errno,
            'error' => $error,
            'data'  => $data,
        );
        echo json_encode($res);
    }
}/*}}}*/
