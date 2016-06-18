<?php

namespace WioForms\FieldConverter;

class Pesel2Date extends AbstractFieldConverter
{
    public function convert($data)
    {
        $year = (int) ($data[0].$data[1]);
        $month = (int) ($data[2].$data[3]);
        $day = (int) ($data[4].$data[5]);

        $century = 1900;
        if ($month > 12) {
            $month -= 20;
            $century += 100;
        }

        return sprintf('%02d', $month).'/'.sprintf('%02d', $day).'/'.($century + $year);
    }
}
