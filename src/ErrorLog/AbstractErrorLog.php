<?php

namespace WioForms\ErrorLog;

abstract class AbstractErrorLog
{
    abstract public function errorLog($message);

    abstract public function showLog();
}
