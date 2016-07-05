<?php

namespace WioForms\Service;

class LangService
{
    private $langsArray;
    private $wioForms;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;

        $this->langsArray = json_decode(
            file_get_contents(
                $this->wioForms->settings['LangsFilePath']
            ),
            true
        );
    }

    public function getLang($langKey)
    {
        if (isset($this->langsArray[$langKey])) {
            return $this->langsArray[$langKey];
        }

        return $langKey;
    }
}
