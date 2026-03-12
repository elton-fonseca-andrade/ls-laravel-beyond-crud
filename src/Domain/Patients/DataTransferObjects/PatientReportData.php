<?php

namespace Domain\Patients\DataTransferObjects;

use Illuminate\Support\Collection;

class PatientReportData
{
    public function __construct(
        public readonly int $total_admitted,
        public readonly int $total_discharged,
        public readonly Collection $patients,
    ) {}
}
