<?php

namespace WioForms\ContainerRenderer;

class BackLink extends AbstractContainerRenderer
{
    public function showHead()
    {
        $title = 'Wstecz';
        if ($this->title) {
            $title = $this->title;
        }

        $html = '<button class="small-back-button" name="_wioFormsGoBackOneSite" value="true">'.$title.'</button>';

        return $html;
    }

    public function showTail()
    {
        $html = '';

        return $html;
    }
}
