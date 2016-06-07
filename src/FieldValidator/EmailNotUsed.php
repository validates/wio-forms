<?php
namespace WioForms\FieldValidator;

use SuperW\Repository\Recruitment as RecruitmentRepository;

class EmailNotUsed extends AbstractFieldValidator
{

    public function validatePHP($value, $settings)
    {
        global $queryBuilder;
        $recruitmentRepository = new RecruitmentRepository($queryBuilder);
var_dump($this->wioForms);die;
        if (is_null($recruitmentRepository->getFirstByEmailAndWioFlowId($value, 12))) {
            $this->valid = true;
        }
        var_dump($test);die;
        $this->invalidMessage = 'email_used';

        if (filter_var($value, FILTER_VALIDATE_EMAIL))
        {
            $this->valid = true;
        }

        $this->setAnswer();
        return $this->getReturn();
    }
}
