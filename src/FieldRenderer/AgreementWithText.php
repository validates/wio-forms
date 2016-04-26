<?php
namespace WioForms\FieldRenderer;

class AgreementWithText extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = '';

        $html .= '<div style="width: 35%;">';
        $html .= $this->standardErrorDisplay('<br/>');

        $checked = '';
        if ($this->value)
        {
            $checked = ' checked="checked"';
        }
        $html .= '<input type="hidden" name="'.$this->fieldName.'" value="" />';
        $html .= '<input type="checkbox" name="'.$this->fieldName.'"'.$checked.' value="jest" style="float: left;" /> ';


        $html .= $this->fieldInfo['title'].'<br/>';
        $html .= '<span style="font-size: 0.7em;">'.$this->fieldInfo['longer_text'].'</span>';


        $html .= '</div>'."\n";

        return $html;
    }

    function showToView()
      {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
