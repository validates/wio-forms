<?php

namespace WioForms\Service;

class ClassFinderService
{
    private $errorLog;

    public function __construct($errorLog)
    {
        $this->errorLog = $errorLog;
    }

    public function checkName($folderName, $className)
    {

        if (substr($className,0,1) === '\\') {
            $pathName = $className;
        } else {
            $pathName = 'WioForms\\'.$folderName.'\\'.$className;            
        }

        if (class_exists($pathName)) {
            return $pathName;
        } else {
            $this->errorLog->errorLog('There is no '.$pathName.' class.');

            return false;
        }
    }
}
