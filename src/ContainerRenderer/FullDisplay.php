<?php

namespace WioForms\ContainerRenderer;

class FullDisplay extends AbstractContainerRenderer
{
    public function showHead()
    {
        $this->html = '';
        $this->html .= '<div data-wio-forms="'.$this->containerName.'" class="wioForms_Container">'."\n";
        if ($this->title) {
            $this->html .= '<div class="wioForms_ContainerTitle">'.$this->title.'</div>';
        }

        $this->standardErrorDisplay();

        return $this->html;
    }

    public function showTail()
    {

        $this->html = '<input type="hidden" name="_wioForms_containers[]" value="'.$this->containerName.'">'
            .'</div>'."\n";

        return $this->html;
    }
}
