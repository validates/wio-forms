<?php
namespace WioForms\ContainerValidator;

class FormSaverOk extends AbstractContainerValidator
{

    public function validatePHP(&$container, &$settings)
    {
        $this->invalidMessage = 'fill_all_fields_before_save';

        if (isset($settings['formSaverToCheck']))
        {
            if (isset($this->wioForms->formStruct['FormSavers'][ $settings['formSaverToCheck'] ]['valid'])
              and $this->wioForms->formStruct['FormSavers'][ $settings['formSaverToCheck'] ]['valid'])
            {
                $this->valid = true;
            }
        }

        $this->setAnswer();
        return $this->getReturn();
    }

}
