<?php
/**
 * Created by PhpStorm.
 * User: dpa
 * Date: 08/06/16
 * Time: 16:15.
 */
namespace WioForms\DataRepository;

class WiosnaExperienceData extends AbstractDataRepository
{
    /**
     * @TODO: Napisac logikę dla tego, jak tylko ją poznam ;)
     */
    public function getData($requiredFields)
    {
        $this->data['wiosnaExperience'] = rand(1, 2);
        $this->setRepositoryFlags();

        return $this->data;
    }
}
