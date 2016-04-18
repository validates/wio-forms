<?php
namespace WioForms\ContainerRenderer;

class Button1 extends AbstractContainerRenderer
{

    function showHead(){
        $title = 'Wyślij';
        if (isset($this->containerInfo['title']))
        {
            $title = $this->containerInfo['title'];
        }
        $html = '<input type="submit" value="'.$title.'" />';

        return $html;
    }

    function showTail(){
        $html = '';
        return $html;
    }

}

?>
