<?php
namespace WioForms\FieldConverter;

class DatePicker2DateTime extends AbstractFieldConverter
{

    public function convert($data)
    {
        $dateTab = explode('/',$data);
        return $dateTab['2'].'-'.$dateTab['0'].'-'.$dateTab['1'];
    }
}
