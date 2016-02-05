<?php


include 'QFrameLog.php';
include 'writers/QFrameLogWriterFile.php';
include 'writers/QFrameLogWriterDisplay.php';
include 'writers/QFrameLogWriterFirephp.php';
include 'writers/FirePHP.class.php';


$logPath = dirname(__FILE__);
$log = QFrameLog::getInstance($logPath);


###���������־��ӡ��ҳ����###
#$log->openOutput(QFrameLog::OUTPUT_MODE_ECHO);
###end#####


###���������־��ӡ��ҳ���ϣ� ��ע����ʽ###
#$log->openOutput(QFrameLog::OUTPUT_MODE_COMMENT);
###end#####

###���������־��ӡ�� firebug�Ŀ���̨�� ###
#$log->openOutput(QFrameLog::OUTPUT_MODE_FIREPHP);
###end#####


###�����ֻ�����־��ӡ��ҳ���ϻ��ӡ��firebug�Ŀ���̨�ϣ����뱣���ļ� ###
#$log->setOutputMode(QFrameLog::OUTPUT_MODE_ECHO );
#$log->setOutput('�����־�������ļ���ֻ��ӡ��ҳ���firbug ����̨��');
###end#####


$log->info('����һ����־��Ϣ');
$res = $log->sql('select * from user where qid in (?,?,?)', array(1,2,3));
print_r($res);

/* ����ʱ
Array
(
    [errno] => 1
    [errmsg] => Permission denied - failed to open file: xxxxxx/sql.log.20100709
)

��ȷ
Array
(
    [errno] => 0
)
*/
