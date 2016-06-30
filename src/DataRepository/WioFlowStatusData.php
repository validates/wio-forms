<?php

namespace WioForms\DataRepository;

class WioFlowStatusData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $curr_status = $requiredFields['status']!==false ? $requiredFields['status'] :  1;

        global $queryBuilder;
        $statusList = $queryBuilder->table('wio_flow_status_names')
            ->select('status_id', 'name', 'allowed_next')
            ->where('wio_flow_id', '=', 1)
            ->where('status_id', '=', $curr_status)
            ->first();

        $allowed_next = $statusList->allowed_next.",".$curr_status;
        $allowed_next = explode(",", $allowed_next);

        $statusList = $queryBuilder->table('wio_flow_status_names')
            ->select('status_id', 'name', 'allowed_next')
            ->where('wio_flow_id', '=', 1)
            ->whereIn('status_id', $allowed_next)
            ->get();

        foreach ($statusList as $status) {
            $this->data[$status->status_id] = $status->name;
        }
        $this->setRepositoryFlags();

        return $this->data;
    }
}
