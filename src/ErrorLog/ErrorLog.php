<?php
namespace WioForms\ErrorLog;


class ErrorLog extends AbstractErrorLog
{
    private $messages;

    function _construct(){
        $this->messages = [];
    }

    function ErrorLog( $message ){
        $this->messages[] = $message;
    }

    function __destruct(){
        if (count($this->messages)){
            echo '<br/><b> Error Log: </b><br/>';
            foreach ($this->messages as $m){
                echo $m.'<br/>';
            }
        }
    }

}
