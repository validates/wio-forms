<?php

namespace WioForms\ContainerRenderer;

class Button1 extends AbstractContainerRenderer
{
    public function showHead()
    {
        $title = 'Wyślij';
        if ($this->title) {
            $title = $this->title;
        }
        $html = '<button class="button-big-red">'.$title.'</button>';

        return $html;
    }

    public function showTail()
    {
        $html = '';

        return $html;
    }
}
