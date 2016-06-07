<?php
namespace WioForms\ContainerRenderer;

class SectionDisplay extends AbstractContainerRenderer
{

    function showHead()
    {
        $html = '<fieldset class="edit">' . "\n";
        if ($this->title) {
            $html .= '<legend class="editlabel">' . $this->title . '</legend>';
        }
        if ($this->message) {
            $html .= '<b style="color: red;">' . $this->message . '</b><br/>' . "\n";
        }

        return $html;
    }

    function showTail()
    {
        $html = '</fieldset>' . "\n";

        return $html;
    }

}
