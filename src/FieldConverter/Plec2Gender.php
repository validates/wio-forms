<?php
namespace WioForms\FieldConverter;

class Plec2Gender extends AbstractFieldConverter
{

    public function convert($data)
    {
        $plecMap = [
            1 => 'female',
            2 => 'male'
        ];

        return $plecMap[$data];
    }
}
