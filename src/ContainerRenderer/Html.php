<?php

namespace WioForms\ContainerRenderer;

class Html extends AbstractContainerRenderer
{
    public function showHead()
    {
        $html = '';
        if ($this->containerInfo['Html']) {
            $html = $this->containerInfo['Html'];
        }

        return $html;
    }

    public function showTail()
    {
        $html = '';

        return $html;
    }
}
