<?php
class ErrorController extends QFrameAction
{
    public function errorAction()
    {
        $error = $this->getParam('error_handle');
        $errorResson = $error->exception->getMessage();
//        var_dump($errorResson);
        $this->setNoViewRender(true);
    }
}
?>
