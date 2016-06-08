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
        $recruitmentRepository  = new RecruitmentRepository($queryBuilder);
        $roleConverter          = new Stanowisko2Role();

        $role       = $roleConverter->convert($this->wioForms->entryData['stanowisko']);
        $program    = $this->wioForms->entryData['akcja'];

        $this->valid = !$recruitmentRepository
                            ->ifEmailAlreadyUsed($value, $program, $role);

        $this->setAnswer();
        return $this->getReturn();
    }
}
