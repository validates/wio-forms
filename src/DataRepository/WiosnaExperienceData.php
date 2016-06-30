<?php

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
