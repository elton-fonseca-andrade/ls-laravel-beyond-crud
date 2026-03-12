<?php

namespace Domain\Patients\QueryBuilders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PatientQueryBuilder extends Builder
{
    public function whereAdmittedBetween(Carbon $start, Carbon $end): self
    {
        return $this->whereBetween('admitted_at', [$start, $end]);
    }

    public function whereDischarged(): self
    {
        return $this->whereNotNull('discharged_at');
    }

    public function whereActive(): self
    {
        return $this->whereNull('discharged_at');
    }
}
