<?php

namespace WioForms\ContainerRenderer;

class ErrorJumper extends AbstractContainerRenderer
{
    public function showHead()
    {
        $html = $this->javascriptErrorThrower();

        return $html;
    }

    public function showTail()
    {
        return '';
    }

    private function javascriptErrorThrower()
    {
        $html = '<script type="text/javascript">'."\n";
        $html .= '$(document).ready(function(){'."\n";
        $html .= 'var E = $(".wioForms_ErrorMessage");'."\n";
        $html .= 'if(E.length > 0){'."\n";
        $html .= '$("#tabs > .ui-tabs-nav").prepend("<div style=\"color:red;\">W tym rejonie jest już zrekrutowany lider. Aby nadać status zrekrutowany, wybierz inny region.</div>");'."\n";
        $html .= '}';
        $html .= 'console.log(E.length);'."\n";
        $html .= '});'."\n";
        $html .= '</script>'."\n";

        return $html;
    }
}
