<?php
class QFrameLogWriterDisplay
{
    /**
     * ��־��ʾ����
     */
    public $_logFormat = '[%2$s] - [%4$s] - %5$s';
    
    /**
     * ʱ����ʾ��ʽ
     */
    public $_timeFormat = 'Y-m-d H:i:s';
    
    /**
     * ��־������ʲô��ʽ��ʾ��ҳ���� echo || comment
     */
    public $_displayType = 'echo';
    
    /**
     * �����
     */
    private $_buffer  = array();

    /**
     * ���캯��
     *
     * @param string $displayType ��ʾ����
     * @param array $option ����һЩ����ѡ��
     */
    function __construct($displayType='echo', $option=array())
    {/*{{{*/
        $this->_displayType = $displayType;
        if (!empty($option['logFormat']))
        {
            $this->_logFormat = $option['logFormat'];
        }
        
        if($this->_displayType == 'comment')
        {
            $this->_logFormat .= "\n";
        }
        else
        {
            $this->_logFormat .= "<br>\n";
        }

        /* The user can also change the time format. */
        if (!empty($option['timeFormat']))
        {
            $this->_timeFormat = $option['timeFormat'];
        }

        register_shutdown_function(array(&$this, 'display'));
    }/*}}}*/

    /**
     * ��¼��־
     *
     * @param mixed $message ��־��Ϣ
     * @param string $type ���
     * @return bool
     */
    function log($message, $type)
    {/*{{{*/
        if(!is_string($message))
        {
            $message = print_r($message, true);
        }
        $timestamp = date($this->_timeFormat);
        $ip = empty($_SERVER['REMOTE_ADDR']) ? '' : $_SERVER['REMOTE_ADDR'];
        $this->_buffer[] = QFrameLog::format($this->_logFormat, $ip, $timestamp, '', $type, $message);
        return true;
    }/*}}}*/

    /**
     * ��ʾ��־
     */
    function display()
    {/*{{{*/
        $message = implode('', $this->_buffer);
        if($this->_displayType == 'comment')
        {
            $message = "\n<!-- \n\n{$message}\n -->\n";
        }
        echo $message;
    }/*}}}*/

}
