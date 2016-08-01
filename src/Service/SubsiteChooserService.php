<?php

namespace WioForms\Service;

class SubsiteChooserService
{
    public $wioForms;
    public $formStruct;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    public function getSiteNumber()
    {
        $siteNumber = $maxSiteNumber = $this->wioForms->validatorService->getAvaliableSiteNumber();

        if (isset($_POST['_wioFormsGoBackOneSite'])) {
            $avaliableSites = $this->wioForms->validatorService->getAvaliableSitesArray();
            $currentSite = $_POST['_wioFormsSite'];

            for ($i = 0; $i < count($avaliableSites); ++$i) {
                if ($avaliableSites[$i + 1] == $currentSite) {
                    $siteNumber = $avaliableSites[$i];
                    break;
                }
            }
        }

        return $siteNumber;
    }
}
