<?php
class IndexController extends QFrameAction
{
    public function indexAction()
    {/*{{{*/
        $testInfo = "hello! Install Succ";
        $this->assign('info',$testInfo);

        //�����Ҫ��Ⱦ������ģ�壬��
        //$this->render('tpl/blank',true);
        //��ʾ��ȾtplĿ¼�µ�blank.phtml
    }/*}}}*/
}
?>
