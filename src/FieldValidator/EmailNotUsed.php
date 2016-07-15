<?php

namespace WioForms\FieldValidator;

use SuperW\Repository\Recruitment as RecruitmentRepository;

class EmailNotUsed extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'email_already_used';

        global $queryBuilder;
        $recruitmentRepository = new RecruitmentRepository($queryBuilder);

        if (isset($this->wioForms->formStruct['Fields']['wioFlow']['id'])) {
            $wioFlowId = $this->wioForms->formStruct['Fields']['wioFlow']['id'];
        }

        if (isset($this->wioForms->formStruct['Fields']['wio_flow_id']['value'])) {
            $wioFlowId = $this->wioForms->formStruct['Fields']['wio_flow_id']['value'];
        }

        $this->valid = !$recruitmentRepository->ifEmailAlreadyUsed($value, $wioFlowId);
        $this->setAnswer();

        return $this->getReturn();
    }
}
