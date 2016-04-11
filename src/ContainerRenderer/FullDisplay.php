<?php
namespace WioForms\ContainerRenderer;

class FullDisplay extends AbstractContainerRenderer
{

    function ShowHead(){
        $html = 'Container begin: <br/>';

        return $html;
    }

    function ShowTail(){
        $html = 'Container end: <br/>';

        return $html;
    }

}

?>
