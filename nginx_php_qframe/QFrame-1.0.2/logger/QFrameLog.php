<?php
class QFrameLog
{
    /*{{{*/
    const OUTPUT_MODE_FIREPHP = 'firephp'; //��������Ϣ��ӡ��firebug����̨����Ҫ��װfirebug & firephp �����ֻ֧��firefox
    const OUTPUT_MODE_ECHO    = 'echo';    //ֱ�ӽ�������Ϣ��ӡ��ҳ����
    const OUTPUT_MODE_COMMENT = 'comment'; //��������Ϣ��ע����ʽ<!-- -->��ӡ��ҳ����

    const LOG_TYPE_SYS      = 'system';  //ϵͳ�����δ֪���쳣
    const LOG_TYPE_WARN     = 'warning'; //һЩ������Ϣ
    const LOG_TYPE_INFO     = 'info';    //һ�����־��Ϣ
    const LOG_TYPE_SQL      = 'sql';     // ִ�гɹ���sql
    const LOG_TYPE_SQLERR   = 'sql_err'; // �����sql�������﷨����db����Ĵ���
    const LOG_TYPE_BIZ      = 'biz';     // ҵ�����������־��һ�����ڽӿڷ��񣬼�¼�����post����
    const LOG_TYPE_BIZERR   = 'biz_err'; // ҵ�����֪����Ҳ�Ƕ����ڽӿڷ���
    const LOG_TYPE_SDKTIME  = 'sdk_time';// ����sdk����־��һ���¼���÷�����������ʱ��


    private $_writer     = false;
    private $_output     = false;
    private $_outputMode = 'firephp';
    
    static public $_formatMap = array(
                            '%{ip}'         => '%1$s',
                            '%{timestamp}'  => '%2$s',
                            '%{ident}'      => '%3$s',
                            '%{type}'       => '%4$s',
                            '%{message}'    => '%5$s',
                            '%\{'           => '%%{');
    /*}}}*/

    function __construct($writer)
    {/*{{{*/
        //���writer ֻ���ļ�ģʽ        
        $this->_writer = $writer;
    }/*}}}*/

    /**
     *
     * @param string $logPath ��־����·��
     * @param string $fileMod ����log�ļ���Ȩ��
     * @param string $app ��Ŀ�����Ա���־���д洢
     * @param array $option ����һЩ������
     * @return object
     */
    static public function getInstance($logPath, $fileMod=0777, $app='', $option=array())
    {/*{{{*/
        static $obj;
        if($obj instanceof self)
        {
            return $obj;
        }
        $option['filemode'] = $fileMod;
        $writer = new QFrameLogWriterFile($logPath, $app, $option);
        $obj = new self($writer);
        return $obj;
    }/*}}}*/

    /**
     * @param mixed $msg Ҫ��¼����־��Ϣ���ַ�������������
     * @return bool
     */
    public function sys($msg)
    {/*{{{*/
        return $this->_writeLog($msg, self::LOG_TYPE_SYS);
    }/*}}}*/

    public function warn($msg)
    {/*{{{*/
        return $this->_writeLog($msg, self::LOG_TYPE_WARN);
    }/*}}}*/

    public function info($msg)
    {/*{{{*/
        return $this->_writeLog($msg, self::LOG_TYPE_INFO);
    }/*}}}*/

    /**
     * @param string $sql 
     * @param array $values bind values
     */
    public function sql($sql, $values=array())
    {/*{{{*/
        $msg['sql'] = $sql;
        if(!empty($values))
        {
            $msg['values'] = $values;
        }

        return $this->_writeLog($msg, self::LOG_TYPE_SQL);
    }/*}}}*/

    /**
     * @param mixed $msg ������Ϣ
     */
    public function sqlerr($errmsg, $sql, $values=array())
    {/*{{{*/
        $msg['errmsg'] = $errmsg;
        $msg['sql'] = $sql;
        if(!empty($values))
        {
            $msg['values'] = $values;
        }
        return $this->_writeLog($msg, self::LOG_TYPE_SQLERR);
    }/*}}}*/

    public function biz($msg)
    {/*{{{*/
        return $this->_writeLog($msg, self::LOG_TYPE_BIZ);
    }/*}}}*/

    public function bizerr($msg)
    {/*{{{*/
        return $this->_writeLog($msg, self::LOG_TYPE_BIZERR);
    }/*}}}*/

    /**
     * @param mixed $msg sdk��־һ��������õķ��������ѵ�ʱ���
     */
    public function sdktime($msg)
    {/*{{{*/
        return $this->_writeLog($msg, self::LOG_TYPE_SDKTIME);
    }/*}}}*/

    /**
     *��logType = memcacheʱ������logPath������ $app_$logType.log.date
     * 
     * @param mixed $msg
     * @param string $logType log�������������log�ļ���
     */
    public function log($msg, $logType)
    {/*{{{*/
        return $this->_writeLog($msg, $logType);
    }/*}}}*/

    /**
     * ��openOutput�������ǣ�ʹ�ô˷�����ͨ����־���ᱻ��ʾ����������־Ҳ���ᱻ��¼���ļ�
     *
     * @param mixed $msg ���Ե���Ϣ
     * @param string $viewType echo �� firephp
     */
    public function setOutput($msg, $type=self::LOG_TYPE_INFO, $encoding='gbk')
    {/*{{{*/
        $writer = $this->_getOutputWriter($this->_outputMode);
        if($this->_outputMode == self::OUTPUT_MODE_FIREPHP && $encoding != 'utf8')
        {
            $msg = $this->_convertEncoding($msg, 'utf-8', $encoding);
        }
        $writer->log($msg, $type);
    }/*}}}*/

    /**
     * ����־������򿪺���־�ڼǵ��ļ���ͬʱ������ҳ��ִ����ɺ���OUTPUT_MODE��ʽ��ʾ����
     */
    public function openOutput($outputMode=self::OUTPUT_MODE_FIREPHP)
    {/*{{{*/
        $this->_outputMode = $outputMode;
        $this->_output = true;
    }/*}}}*/

    /**
     * ���õ�����Ϣ��ʾ��ʽ
     *
     * @param string $viewType ��ʾ��ʽ
     */
    public function setOutputMode($outputMode=self::OUTPUT_MODE_FIREPHP)
    {/*{{{*/
        $this->_outputMode = $outputMode;
    }/*}}}*/

    /**
     * ����writer��log������������־��Ϣ
     *
     * @param string $msg ��־����
     * @param string $type ��־����
     * @return array
     */
    private function _writeLog($msg, $type)
    {/*{{{*/
        if($this->_output)
        {
            $this->setOutput($msg, $type);
        }
        $ret['errno'] = 0;
        $result_write = $this->_writer->log($msg, $type);
        if(false == $result_write)
        {
            $ret['errno'] = 1;
            $ret['errmsg'] = $this->_writer->getErrmsg();
        }
        return $ret;
    }/*}}}*/


    /**
     * ��ȡʾ־��ʾ��ʵ��
     *
     * @param string $outputMode
     * @return object
     */
    private function _getOutputWriter($outputMode=self::OUTPUT_MODE_FIREPHP)
    {/*{{{*/
        static $outputWriters;
        if(isset($outputWriters[$outputMode]))
        {
            return $outputWriters[$outputMode];
        }
        if($outputMode == self::OUTPUT_MODE_FIREPHP )
        {
            $writer = new QFrameLogWriterFirephp();
        }
        else
        {
            $writer = new QFrameLogWriterDisplay($outputMode);
        }
        $outputWriters[$outputMode] = $writer;
        return $writer;
    }/*}}}*/

    /**
     * ���뻻��������ݵı���, firephpֻ��ʾutf8�ַ���
     *
     * @param mixed $arr
     * @param string $toEncoding
     * @param string $fromEncoding
     * @param bool $convertKey
     * @return mixed
     */
    function _convertEncoding($arr, $toEncoding, $fromEncoding)
    {/*{{{*/
        if ($toEncoding == $fromEncoding) return $arr;
        if (is_array($arr))
        {
            foreach($arr as $key => $value)
            {
                if (is_array($value))
                {
                    $value = $this->_convertEncoding($value, $toEncoding, $fromEncoding);
                }
                else
                {
                    $value = mb_convert_encoding($value, $toEncoding, $fromEncoding);
                }
                $arr[$key] = $value;
            }
        }
        else
        {
            $arr = mb_convert_encoding($arr, $toEncoding, $fromEncoding);
        }
        return $arr;
    }/*}}}*/
   
    static function format($format, $ip, $timestamp, $ident, $type, $message)
    {/*{{{*/
        return sprintf($format,
                       $ip,
                       $timestamp,
                       $ident =='' ? '-' : $ident,
                       $type == '' ? '-' : $type,
                       $message);
    }/*}}}*/
}
