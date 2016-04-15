<?php
namespace WioForms\FieldValidator;

class NotEmpty extends AbstractFieldValidator
{

    public function validatePHP( $value, $settings ){


        if( !empty( $value ) )
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
