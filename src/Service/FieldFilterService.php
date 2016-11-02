<?php

namespace WioForms\Service;

class FieldFilterService
{
    private $fields;

    public function __construct(&$wioFormsObject)
    {
        $this->wioForms = &$wioFormsObject;
    }

    /**
     * @return  void
     */
    public function filterFields()
    {
        foreach ($this->wioForms->formStruct['Fields'] as &$field) {
            if (isset($field['value']) && isset($field['filters'])) {
                foreach ($field['filters'] as $filterClass) {
                    $filterObject = new $filterClass();
                    $field['value'] = $filterObject->filter($field['value']);
                }
            }
        }
    }
}
