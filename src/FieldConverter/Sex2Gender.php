<?php

namespace WioForms\FieldConverter;

class Sex2Gender extends AbstractFieldConverter
{
    const API_FEMALE = 1;
    const API_MALE = 2;

    public function convert($data)
    {
        $sexMap = [
            $this::API_FEMALE => 'female',
            $this::API_MALE => 'male',
        ];

        if (isset($sexMap[$data])) {
            return $sexMap[$data];
        }

        return '';
    }
}
