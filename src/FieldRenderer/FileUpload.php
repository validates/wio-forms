<?php

namespace WioForms\FieldRenderer;

class FileUpload extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<input type="hidden" name="'.$this->fieldName.'" value="'.$this->value.'" />';

        if (empty($this->value)) {
            $this->html .= '<input type="file" name="'.$this->fieldName.'_file" />';
        } else {
            $this->html .= '<div class="closeSpanOpenDiv">';
            $this->html .= '<span>Plik wgrany pomyślnie. Zmień</span>';
            $this->html .= '<div class="thisIsReadyToOpen" style="display: none;">';

            $this->html .= '<input type="file" name="'.$this->fieldName.'_file" />';

            $this->html .= '</div></div>';
        }

        $this->html .= $this->javascriptAction();

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
    }

    private function javascriptAction()
    {
        $html = '<script type="text/javascript">';
        $html .= '$(".closeSpanOpenDiv .thisIsReadyToOpen").hide();';
        $html .= '$(".closeSpanOpenDiv span").click(function(){ $(this).slideUp(); $(this).parent().children(".thisIsReadyToOpen").slideDown(); });';
        $html .= '</script>';

        return $html;
    }
}
