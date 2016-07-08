<?php

namespace WioForms\FieldRenderer;

class UploadedFileLink extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        if (isset($this->value->fileLink) and isset($this->value->fileName)) {
            $link = str_replace('../', '/', $this->value->fileLink);

            $this->html .= '<a href="'.$link.'" style="color: blue; font-weight: bold; text-decoration: underline;" target="_blank">'.$this->value->fileName.'</a>';
        } else {
            $this->html .= 'Brak pliku.';
        }

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'PasswordInput: '.'********'.'<br/>'."\n";

        return $html;
    }
}
