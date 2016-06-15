<?php

namespace WioForms\ErrorLog;

class ErrorLog extends AbstractErrorLog
{
    private $messages;

    public function _construct()
    {
        $this->messages = [];
    }

    public function errorLog($message)
    {
        $this->messages[] = $message;
    }

    public function __destruct()
    {
        $this->showLog();
    }

    public function showLog()
    {
        return '';
        if (count($this->messages)) {
            echo '<br/><b> Error Log: </b><br/>';
            foreach ($this->messages as $m) {
                echo $m.'<br/>';
            }
        }
    }
}
