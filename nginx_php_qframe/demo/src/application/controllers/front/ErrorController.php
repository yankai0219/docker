<?php
class ErrorController extends QFrameAction
{
    public function errorAction()
    {
        $error = $this->getParam('error_handle');
        $errorResson = $error->exception->getMessage();
        echo __METHOD__ . print_r($errorResson) . "\n";
        $this->setNoViewRender(true);
    }
}
?>
