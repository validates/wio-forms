<?php

namespace WioForms\ContainerRenderer;

class Html extends AbstractContainerRenderer
{
    public function showHead()
    {
        $html = '';
        if ($this->Html) {
            $html = $this->Html;
        }

        return $html;
    }

    public function showTail()
    {
        $html = '';

        return $html;
    }
}
