<?php
namespace WioForms\ContainerRenderer;

class FullDisplay extends AbstractContainerRenderer
{

    function ShowHead(){

        $html = '<div style="margin: 40px 20px; padding: 20px;border: 2px dashed black; position: relative;">';
        if ( isset( $this->ContainerInfo['title'] ) and !empty( $this->ContainerInfo['title'] ) )
        {
            $html .= '<div style="border: 2px dashed black; background-color: white; position: absolute; top: -17px; left: 20px; padding: 5px 10px; display: inline-block;">'.$this->ContainerInfo['title'].'</div>';

        }

        return $html;
    }

    function ShowTail(){
        $html = '</div>';

        return $html;
    }

}

?>
