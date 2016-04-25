<?php
namespace WioForms\FieldValidator;

class NotEmpty extends AbstractFieldValidator
{

    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'field_required';

        if (!empty($value))
        {
            $this->valid = true;
        }

        $this->setAnswer();
        return $this->getReturn();
    }
}
?>
