<?php
namespace WioForms\FieldConverter;

class Pesel2Date extends AbstractFieldConverter
{
    public function convert($data)
    {
        if (empty($data)) {
            return $data;
        }
        $year =  (Int)($data[0].$data[1]);
        $month = (Int)($data[2].$data[3]);
        $day =   (Int)($data[4].$data[5]);

        $century = 1900;
        if ($month > 12)
        {
            $month -= 20;
            $century += 100;
        }

        return sprintf('%02d',($century+$year)).'-'.sprintf('%02d',$month).'-'.$day;
    }
}
