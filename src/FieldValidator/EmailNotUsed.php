<?php

namespace WioForms\FieldValidator;

use SuperW\Repository\Recruitment as RecruitmentRepository;
use WioForms\FieldConverter\Stanowisko2Role;

class EmailNotUsed extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'email_already_used';

        global $queryBuilder;
        $recruitmentRepository = new RecruitmentRepository($queryBuilder);

        $this->valid = !$recruitmentRepository
                            ->ifEmailAlreadyUsed($value, $this->wioForms->formStruct['Fields']['wioFlow']['id']);

        $this->setAnswer();

        return $this->getReturn();
    }
}
