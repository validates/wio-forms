<?php
namespace WioForms\ContainerRenderer;

class Button1 extends AbstractContainerRenderer
{

    function ShowHead(){
        $title = 'Wyślij';
        if (isset($this->ContainerInfo['title']))
        {
            $title = $this->ContainerInfo['title'];
        }
        $html = '<input type="submit" value="'.$title.'" />';

        return $html;
    }

    function ShowTail(){
        $html = '';
        return $html;
    }

}

?>
