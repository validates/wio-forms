<?php
namespace WioForms\ContainerRenderer;

class FullDisplay extends AbstractContainerRenderer
{

    function showHead()
    {
        $html = '<div class="wioForms_Container">'."\n";
        if ($this->title)
        {
            $html .= '<div class="wioForms_ContainerTitle">'.$this->title.'</div>';
        }
        if ($this->message)
        {
            $html .= '<b style="color: red;">'.$this->message.'</b><br/>'."\n";
        }

        return $html;
    }

    function showTail()
    {
        $html = '</div>'."\n";

        return $html;
    }

}
