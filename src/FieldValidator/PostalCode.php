<?php

namespace WioForms\FieldValidator;

class PostalCode extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'postal_code_invalid';

        if (preg_match('/^([0-9]{2})(-[0-9]{3})?$/i', $value)) {
            $this->valid = true;
        }

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
