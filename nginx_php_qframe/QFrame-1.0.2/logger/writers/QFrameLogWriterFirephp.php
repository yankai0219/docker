<?php
class QFrameLogWriterFirephp
{
    /**
     * ʱ����ʾ��ʽ
     */
    private $_timeFormat = 'H:i:s';

    /**
     * ������
     */
    static private $_buffer = array();

    /**
     * ���캯��
     */
    function __construct($option=array())
    {/*{{{*/
        if (!empty($option['timeFormat']))
        {
            $this->_timeFormat = $option['timeFormat'];
        }
        register_shutdown_function(array(&$this, 'show'));
    }/*}}}*/

    /**
     * �ռ���־��Ϣ
     *
     * @param string $message
     * @param string $type
     * @return bool
     */
    public function log($message, $type)
    {/*{{{*/
        $data['logdate'] = date($this->_timeFormat);
        $data['logtype'] = $type;

        $message = str_replace("\r\n", "\n", $message);
        $message = str_replace("\n", "\\n\\\n", $message);
        $message = str_replace('"', '\\"', $message);

        $data['message'] = $message;
        $type = $this->_getType($type);
        if(substr($type, 0, 3) == 'sql')
        {
            $data = $this->_formatMessageSql($data);
        }
        self::$_buffer[$type][] = $data;
        return true;
    }/*}}}*/

    /**
     * �ѻ������е���־��Ϣ��ӡ��firebug����̨
     */
    public function show()
    {/*{{{*/
        foreach(array('error', 'warn', 'info') as $type)
        {
            if(empty(self::$_buffer[$type])) continue;
            $logContent = array();
            self::fb(null, $type, FirePHP::GROUP_START); // ���鿪ʼ
            foreach(self::$_buffer[$type] as $info)
            {
                self::fb($info['message'], $info['logdate'] . " [{$info['logtype']}] ", $this->_getFirephpType($type));
            }
            self::fb(null, $type, FirePHP::GROUP_END  ); // �ָ�����
        }

        foreach(array('sql', 'sql_err') as $type)
        {
            if(empty(self::$_buffer[$type])) continue;
            $logContent = array();
            if($type == 'sql_err')
            {
                $logContent[] = array('timestamp', 'errmsg', 'sql', 'values'); // table �ı�����
            }
            else
            {
                $logContent[] = array('timestamp', 'sql', 'values');
            }

            foreach(self::$_buffer[$type] as $info)
            {
                if($type == 'sql_err')
                {
                    $logContent[] = array($info['logdate'], $info['errmsg'], $info['sql'],  $info['values']);  // table�ĸ���
                }
                else
                {
                    $logContent[] = array($info['logdate'], $info['sql'],  $info['values']);
                }
            }
            self::fb(array($type, $logContent), FirePHP::TABLE);
        }
    }/*}}}*/

    static public function fb()
    {/*{{{*/
        $instance = FirePHP::getInstance(true);
        $args = func_get_args();
        return call_user_func_array(array($instance,'fb'),$args);
    }/*}}}*/

    /**
     * ����־�������־����
     *
     * @param string $type
     * @return string
     */
    private function _getType($type)
    {/*{{{*/
        switch ($type)
        {
            case 'system':
            case 'error':
                $type = 'error' ;
                break;
            case 'warning':
                $type = 'warn' ;
                break;
            case 'sql':
                $type = 'sql';
                break;
            case 'sql_err':
                $type = 'sql_err';
                break;
            default:
                $type = 'info'  ;
                break;
        }
        return $type;
    }/*}}}*/

    /**
     * ��ʽ��sql��־��Ϣ
     *
     * @param array $message
     * @return array
     */
    private function _formatMessageSql($message)
    {/*{{{*/
        $message['sql'] = $message['message']['sql'];
        $message['values'] = $message['message']['values'];
        if(isset($message['message']['errmsg']))
        {
            $message['errmsg'] = $message['message']['errmsg'];
        }
        return $message;
    }/*}}}*/

    /**
     * ������־��ʽ��ȡ��firephp�е���ʾ��ʽ
     *
     * @param string $type
     * @return string
     */
    private function _getFirephpType($type)
    {/*{{{*/
        $firephpType = FirePHP::INFO ;
        switch($type)
        {
            case 'error':
                $firephpType = FirePHP::ERROR;
                break;
            case 'warn':
                $firephpType = FirePHP::WARN ;
                break;
            case 'info':
                $firephpType = FirePHP::INFO ;
                break;
            case 'sql':
            case 'sql_err':
                $firephpType = FirePHP::Table;
                break;
            default:
                $firephpType = FirePHP::INFO ;
                break;
        }
        return $firephpType;
    }/*}}}*/
}

