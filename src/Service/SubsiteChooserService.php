<?php

namespace WioForms\Service;

/**
 * This service finding the siteNumber used by RendererService in method renderFormSite.
 * In multi-sites forms by default is choosen the site with highest site number, where is something to display.
 * To show the sites with lower numbers we should set all containers in higher sites to hidden. This operation should be made by container validators.
 *
 * Additionally this service can change the siteNumber reading commands form $_POST.
 * For example "goBackOneSite"
 */
class SubsiteChooserService
{
    public $wioForms;
    public $formStruct;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    /*
     * This method searches throu all the containers in form and searching for not-hidden containers that are directly under site containers.
     * Numbers of sites with these containers are writen into return array.
     */
    private function getAvailableSites()
    {
        $availableSites = [];

        foreach ($this->formStruct['Containers'] as $container) {
            if ($container['container'] == '_site'
              and !(isset($container['hidden']) and $container['hidden'])) {
                $availableSites[] = $container['site'];
            }
        }
        sort($availableSites);

        return $availableSites;
    }

    public function getSiteNumber()
    {
        $availableSites = $this->getAvailableSites();

        /*
         * Here we getting highest available site number with any visible container.
         */
        $siteNumber = max($availableSites);

        /*
         * If _wioFormsGoBackOneSite is set, (and it can be so, for example by container BackLink), we search for available site previous to site we currently been on.
         */
        if (isset($_POST['_wioFormsGoBackOneSite'])) {
            $currentSite = (int) $_POST['_wioFormsSite'];

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
