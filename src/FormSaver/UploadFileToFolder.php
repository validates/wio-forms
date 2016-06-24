<?php

namespace WioForms\FormSaver;

class UploadFileToFolder extends AbstractFormSaver
{


    public function makeSavingAction($settings)
    {

        $fieldName = $settings['field'];
        $dir = $settings['dir'];

        $fileData = $_FILES[$fieldName.'_file'];



        var_dump($fileData);


    }

}
