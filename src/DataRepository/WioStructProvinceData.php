<?php

namespace WioForms\DataRepository;

use Pixie\QueryBuilder\QueryBuilderHandler;
use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class WioStructProvinceData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $this->data = [];

        $wioStruct = new WioStruct(new QueryBuilderHandler());

        $provinceList = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('administrative')
                ->nodeTypeName('state')
            )
            ->get('Node');

        foreach ($provinceList as $province) {
            $this->data[$province->NodeId] = $province->NodeName;
        }

        $this->setRepositoryFlags();

        return $this->data;
    }
}
