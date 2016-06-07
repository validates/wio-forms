<?php
namespace WioForms\ContainerRenderer;

class FullDisplay extends AbstractContainerRenderer
{

    function showHead()
    {
        $this->html = '';
        $this->html .= '<div data-wio-forms="' . $this->containerName . '" class="wioForms_Container">'."\n";
        if ($this->title)
        {
            $this->html .= '<div class="wioForms_ContainerTitle">'.$this->title.'</div>';
        }

        $this->standardErrorDisplay();

        return $this->html;
    }

    function showTail()
    {
        $this->html = '</div>'."\n";

        return $this->html;
    }

}
