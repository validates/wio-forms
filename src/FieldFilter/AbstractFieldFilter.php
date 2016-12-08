<?php

namespace WioForms\FieldFilter;

abstract class AbstractFieldFilter
{
    /**
     * Filters data.
     *
     * @param   mixed
     *
     * @return mixed
     */
    abstract public function filter($data);
}
