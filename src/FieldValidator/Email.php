<?php
namespace WioForms\FieldValidator;

class Email extends AbstractFieldValidator
{

    public function validatePHP( $value, $settings ){

        if(  filter_var( $value, FILTER_VALIDATE_EMAIL ))
        {
            $this->state = 1;
            $this->valid = true;
        }
        else
        {
            $this->state = -1;
            $this->valid = false;
            $this->message = 'email_invalid';
        }

        return $this->getReturn();
    }

}
?>
