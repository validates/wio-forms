<?php
namespace WioForms\ContainerRenderer;

class Button1 extends AbstractContainerRenderer
{

    function showHead()
    {
        $title = 'Wyślij';
        if ($this->title)
        {
            $title = $this->title;
        }
        $html = '<button class="button-big-red">'.$title.'</button>';

        return $html;
    }

    function showTail()
    {
        $html = '';
        return $html;
    }

}
