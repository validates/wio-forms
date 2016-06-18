<?php

namespace WioForms\FieldValidator;

class PhoneNumber extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'phone_number_to_short';

        $value = str_replace(
          array(' ', '-', '.', '+', '(', ')'),
          '',
          $value
        );

        if (strlen($value) >= 9 and is_numeric($value)) {
            $this->valid = true;
        }

        $this->setAnswer();

        return $this->getReturn();
    }
}
