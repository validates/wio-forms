<?php
namespace WioForms\DataRepository;

class WioFlowStatusData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;
        $statusList = $queryBuilder->table('wio_flow_status_names')
            ->select('status_id', 'name')
            ->where('wio_flow_id', '=', 1)
            ->get();

        foreach ($statusList as $status) {
            $this->data[$status->status_id] = $status->name;
        }
        $this->setRepositoryFlags();

        return $this->data;
    }

}
