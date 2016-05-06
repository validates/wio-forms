<?php
namespace WioForms\FieldConverter

class Password2Hash extends AbstractFieldConverter
{

    public function convert($data)
    {
        return md5($data);
    }
}
