<?php

namespace Domain\Patients\Actions;

use Carbon\Carbon;
use Domain\Patients\DataTransferObjects\PatientReportData;
use Domain\Patients\Models\Patient;

class GeneratePatientReportAction
{
    public function execute(Carbon $start, Carbon $end): PatientReportData
    {
        $patients = Patient::query()
            ->whereAdmittedBetween($start, $end)
            ->get();

        return new PatientReportData(
            total_admitted: $patients->activePatients()->count(),
            total_discharged: $patients->dischargedPatients()->count(),
            patients: $patients,
        );
    }
}
