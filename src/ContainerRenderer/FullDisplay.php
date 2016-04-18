<?php
namespace WioForms\ContainerRenderer;

class FullDisplay extends AbstractContainerRenderer
{

    function showHead(){

        $html = '<div style="margin: 40px 20px; padding: 20px;border: 2px dashed black; position: relative;">';
        if ( isset( $this->containerInfo['title'] ) and !empty( $this->containerInfo['title'] ) )
        {
            $html .= '<div style="border: 2px dashed black; background-color: white; position: absolute; top: -17px; left: 20px; padding: 5px 10px; display: inline-block;">'.$this->containerInfo['title'].'</div>';

        }

        return $html;
    }

    function showTail(){
        $html = '</div>';

        return $html;
    }

}

?>
