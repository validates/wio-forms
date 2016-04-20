<?php
namespace WioForms\Service;

class ClassFinderService
{
    private $errorLog;

    function __construct($errorLog){
        $this->errorLog = $errorLog;

    }

    public function checkName( $folderName, $className ){
        $pathName = 'WioForms\\'.$folderName.'\\'.$className;

        if ( class_exists($pathName) )
        {
            return $pathName;
        }
        else
        {
            $this->errorLog->errorLog('There is no '.$pathName.' class.');
            return false;
        }
    }
}

?>
