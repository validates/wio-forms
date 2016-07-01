<?php

namespace WioForms\ContainerValidator;

class FileReadyToUpload extends AbstractContainerValidator
{
    public function validatePHP(&$container, &$settings)
    {
        $fieldName = $settings['field'];

        if (isset($_FILES[$fieldName.'_file']) and strlen($_FILES[$fieldName.'_file']['name']) > 0) {
            $this->valid = true;
        }

        var_dump($this->valid);

        $this->setAnswer();

        return $this->getReturn();
    }
}
