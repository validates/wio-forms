<?php
namespace WioForms\FieldRenderer;

class AgreementWithText extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead('wioForms_AgreementWithText');
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $checked = '';
        if ($this->value)
        {
            $checked = ' checked="checked"';
        }
        $this->html .= '<input type="hidden" name="'.$this->fieldName.'" value="" />';
        $this->html .= '<input type="checkbox" name="'.$this->fieldName.'"'.$checked.' value="jest" style="float: left;" /> ';

        $this->html .= '<span class="wioForms_InputLongerText">'.$this->fieldInfo['longer_text'].'</span>';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    function showToView()
      {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
