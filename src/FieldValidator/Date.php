<?php
namespace WioForms\FieldValidator;

class Date extends AbstractFieldValidator
{

    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'date_invalid';

        if (preg_match("/^[0-1][0-9]\/[0-3][0-9]\/[0-9]{4}$/",$value))
        {
            $this->valid = true;
        }

        $this->setAnswer();
        return $this->getReturn();
    }
}
?>
