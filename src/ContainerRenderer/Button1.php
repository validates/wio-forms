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
        $html = '<input type="submit" value="'.$title.'" />'."\n";

        return $html;
    }

    function showTail()
    {
        $html = '';
        return $html;
    }

}

?>
