<?php
namespace WioForms\FieldRenderer;

class DatePicker extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->wioForms->headerCollectorService->addJS('assets/js/jquery-1.12.3.min.js');
        $this->wioForms->headerCollectorService->addJS('assets/js/jquery-ui.min.js');
        $this->wioForms->headerCollectorService->addJS('assets/js/jquery-ui.datepicker-pl.js');
        $this->wioForms->headerCollectorService->addCss('assets/jquery-ui.min.css');
        $this->wioForms->headerCollectorService->addCss('assets/jquery-ui.structure.min.css');
        $this->wioForms->headerCollectorService->addCss('assets/jquery-ui.theme.min.css');

        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<script type="text/javascript">$(function(){ $(".datePicker_'.$this->fieldName.'").datepicker({changeMonth: true, changeYear: true}); });</script>';
        $this->html .= '<input type="text" name="'.$this->fieldName.'" class="datePicker_'.$this->fieldName.'" value="'.$this->value.'" />';

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
