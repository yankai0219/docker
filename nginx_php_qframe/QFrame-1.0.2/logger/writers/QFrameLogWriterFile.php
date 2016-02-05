<?php
class QFrameLogWriterFile 
{
    /**
     * ��־�ļ�������ʽ
     */
    private $_filename  = '{app}{logtype}.log.{logtime}';
    /**
     * д�ļ�ʱ�Ƿ������Ĭ�ϲ���
     */
    private $_locking   = false;
    
    /**
     * �����µ���־�ļ�Ӧ�ø���Ȩ��
     */
    private $_filemode  = 0777;
    
    /**
     * ������־�ļ�����Ȩ��
     */
    private $_dirmode   = 0755;
    
    /**
     * �ռǸ�ʽ��Ҫ��¼��Щ��
     */
    private $_logFormat = '%1$s %2$s %5$s';
    
    /**
     * ʱ���ʽ
     */
    private $_timeFormat= 'Y-m-d H:i:s';
    
    /**
     * ÿһ����־��ʲô��ʽ��β
     */
    private $_eol       = "\n";
    
    /**
     * ��־��ʵʱд����̻��ǻ���д��
     */
    private $_buffering = false; 
    
    /**
     * ��־�ļ�����Ŀ¼
     */
    private $_logPath   = '';
    
    /**
     * ��ʶ
     */
    private $_ident     = '';
    
    /**
     * ������Ϣ����д��־����ʱ�����������Ϣ
     */
    private $_errmsg    = '';

    /**
     * ������
     */
    private $_buffer = array();
    
    /**
     * �򿪵���־�ļ������
     */
    private $_handle = array();
    
    /**
     * @param string $logPath ��־����Ŀ¼
     * @param string $ident ��ʶ��
     * @param array $option ��ѡ��
     */
    function __construct($logPath, $ident='', $option = array())
    {/*{{{*/
        $this->_logPath = $logPath;
        $this->_ident = $ident;

        if (isset($option['locking']))
        {
            $this->_locking = $option['locking'];
        }

        if (!empty($option['filemode']))
        {
            $this->_filemode = $option['filemode'];
        }

        if (!empty($option['dirmode']))
        {
            $this->_dirmode = $option['dirmode'];
        }

        if (!empty($option['buffering']))
        {
            $this->_buffering = $option['buffering'];
        }

        if (!empty($option['eol']))
        {
            $this->_eol = $option['eol'];
        }
        else
        {
            $this->_eol = (strstr(PHP_OS, 'WIN')) ? "\r\n" : "\n";
        }

        if($this->_buffering)
        {
            register_shutdown_function(array(&$this, 'flush'));
        }
    }/*}}}*/

    /**
     * �ر����д򿪵���־�ļ����
     */
    function __destruct()
    {/*{{{*/
        foreach($this->_handle as $fp)
        {
            if($fp) fclose($fp);
        }
    }/*}}}*/

    /**
     * ��¼��־������buffering �ж���ֱ��д�룬�������ռ���д��
     *
     * @param string $message
     * @param string $type
     * @return bool
     */
    public function log($message, $type)
    {/*{{{*/
        if(!is_string($message))
        {
            $message = print_r($message, true);
        }
        $message = $this->_formatMessage($message, $type);
        if($this->_buffering)
        {
            $this->_buffer[$type][] = $message;
            return true;
        }
        return $this->_writeLog($message, $type);
    }/*}}}*/

    /**
     * ������־�ļ����־û��洢
     */
    public function flush()
    {/*{{{*/
        $this->_writeLog($this->_buffer);
    }/*}}}*/

    /**
     * ��ȡ������Ϣ
     *
     * @return unknown
     */
    public function getErrmsg()
    {/*{{{*/
        return $this->_errmsg;
    }/*}}}*/

    /**
     * ��ʽ����־��Ϣ
     *
     * @param string $message
     * @return string
     */
    private function _formatMessage($message, $type)
    {/*{{{*/
        $timestamp = date($this->_timeFormat, time());
        $ip = empty($_SERVER['REMOTE_ADDR']) ? '' : $_SERVER['REMOTE_ADDR'];
        return QFrameLog::format($this->_logFormat, $ip, $timestamp, $this->_ident, $type, $message);
    }/*}}}*/

    /**
     * д��־
     *
     * @param string $message
     * @param string $type
     * @return bool
     */
    private function _writeLog($message, $type='')
    {/*{{{*/
        $filename['{app}']      = empty($this->_ident) ? '' : $this->_ident . '_';
        $filename['{logtype}']  = $type;
        $filename['{logtime}']  = date('Ymd');

        $msgContent = '';
        if(is_string($message))
        {
            $msgContent = $message . $this->_eol;
            $filename = $this->_logPath . '/' . strtr($this->_filename, $filename);
            return $this->_writeToFile($filename, $msgContent);
        }
        $result_write = true;
        foreach($message as $type => $msglist)
        {
            $msgContent = '';
            foreach($msglist as $line)
            {
                $msgContent .= $line . $this->_eol;
            }
            $filename['{logtype}'] = $type;
            $filepath = $this->_logPath . '/' . strtr($this->_filename, $filename);
            $success = $this->_writeToFile($filepath, $msgContent);
            if(false == $success)
            {
                $result_write = $success;
            }
        }
        return $result_write;
    }/*}}}*/

    /**
     * ����־����д���ļ�
     *
     * @param string $filename
     * @param string $content
     * @return bool
     */
    private function _writeToFile($filename, $content)
    {/*{{{*/
        $fp = $this->_open($filename);
        if(false == $fp)
        {
            return $fp;
        }

        if ($this->_locking)
        {
            flock($fp, LOCK_EX);
        }

        $success = (fwrite($fp, $content) !== false);

        if ($this->_locking)
        {
            flock($fp, LOCK_UN);
        }

        return $success;
    }/*}}}*/

    /**
     * ����־�ļ�
     *
     * @param string $filename ��־�ļ�����·��
     * @return resource
     */
    private function _open($filename)
    {/*{{{*/
        $key = md5($filename);
        if(isset($this->_handle[$key]) && is_resource($this->_handle[$key]))
        {
            return $this->_handle[$key];
        }
        /* If the log file's directory doesn't exist, create it. */
        if (!is_dir(dirname($filename)))
        {
            $createDir = $this->_mkpath($filename, $this->_dirmode);
            if(false == $createDir)
            {
                return $createDir;
            }
        }

        /* Determine whether the log file needs to be created. */
        $creating = !file_exists($filename);

        /* Obtain a handle to the log file. */
        $fp = fopen($filename, 'a');
        if(false === $fp)
        {
            $this->_errmsg = 'Permission denied - failed to open file: ' . $filename ;
            return $fp;
        }

        /* Attempt to set the file's permissions if we just created it. */
        if ($creating && $fp) {
            chmod($filename, $this->_filemode);
        }

        $this->_handle[$key] = $fp;
        return $fp;
    }/*}}}*/

    /**
     * ������־Ŀ¼
     *
     * @param string $path ��־�ļ�·��
     * @param mixed $mode 
     * @return bool
     */
    private function _mkpath($path, $mode = 0755)
    {/*{{{*/
        /* Separate the last pathname component from the rest of the path. */
        $head = dirname($path);
        $tail = basename($path);

        /* Make sure we've split the path into two complete components. */
        if (empty($tail))
        {
            $head = dirname($path);
            $tail = basename($path);
        }

        $success = mkdir($head, $mode, true);
        if(false == $success)
        {
            $this->_errmsg = 'Permission denied - Warning: mkdir() '. $head ;
            return $success;
        }
        return $success;
    }/*}}}*/
}
