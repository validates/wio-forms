<?php

namespace WioForms\ContainerRenderer;

class SectionDisplay extends AbstractContainerRenderer
{
    public function showHead()
    {
        $this->html = '<fieldset class="edit">'."\n";
        if ($this->title) {
            $this->html .= '<legend class="editlabel">'.$this->title.'</legend>';
        }

        $this->standardErrorDisplay();

        return $this->html;
    }

    public function showTail()
    {
        $html = '<input type="hidden" name="_wioForms_containers[]" value="'.$this->containerName.'">'
            .'</fieldset>'."\n";

        return $html;
    }
}
