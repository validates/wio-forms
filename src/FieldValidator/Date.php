<?php

namespace WioForms\FieldValidator;

class Date extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'date_invalid';

        if (preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $value)) {
            $dateParts = explode('/', $value);
            if (checkdate($dateParts[0], $dateParts[1], $dateParts[2])) {
                $this->valid = true;
            }
        } else {
            $this->invalidMessage = 'date_invalid_format';
        }

        $this->setAnswer();

        return $this->getReturn();
    }
}
