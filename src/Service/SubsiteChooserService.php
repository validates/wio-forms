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
        $siteNumber = $maxSiteNumber = $this->wioForms->validatorService->getAvailableSiteNumber();

        if (isset($_POST['_wioFormsGoBackOneSite'])) {
            $availableSites = $this->wioForms->validatorService->getAvailableSitesArray();
            $currentSite = $_POST['_wioFormsSite'];

            for ($i = 0; $i < count($availableSites); ++$i) {
                if ($availableSites[$i + 1] == $currentSite) {
                    $siteNumber = $availableSites[$i];
                    break;
                }
            }
        }

        return $siteNumber;
    }
}
