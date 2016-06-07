<?php
namespace WioForms\FieldRenderer;

class Link extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = '<a class="'.$this->fieldInfo['class'].'" href="'.$this->fieldInfo['url'].'">'.$this->fieldInfo['title'].'</a>'."\n";

        return $html;
    }

    function showToView()
    {
        $html = 'Link: '.'http://www.org.pl'.'<br/>'."\n";

        return $html;
    }
}
