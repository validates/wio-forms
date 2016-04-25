<?php
namespace WioForms\FieldRenderer;

class DatePicker extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = '';

        $this->wioForms->headerCollectorService->addJS('assets/js/jquery-1.12.3.min.js');
        $this->wioForms->headerCollectorService->addJS('assets/js/jquery-ui.min.js');
        $this->wioForms->headerCollectorService->addJS('assets/js/jquery-ui.datepicker-pl.js');
        $this->wioForms->headerCollectorService->addCss('assets/jquery-ui.min.css');
        $this->wioForms->headerCollectorService->addCss('assets/jquery-ui.structure.min.css');
        $this->wioForms->headerCollectorService->addCss('assets/jquery-ui.theme.min.css');

        $html .= '<script type="text/javascript">$(function(){ $(".datePicker_'.$this->fieldName.'").datepicker(); });</script>';

        $html .= $this->fieldInfo['title'].': ';

        $html .= '<input type="input" name="'.$this->fieldName.'" class="datePicker_'.$this->fieldName.'" value="'.$this->value.'" />';

        $html .= $this->standardErrorDisplay();
        $html .= '<br/>'."\n";

        return $html;
    }

    function showToView()
      {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
