<?php

namespace App\Admin\Patients\ViewModels;

use Carbon\Carbon;
use Domain\Patients\Actions\GeneratePatientReportAction;
use Domain\Patients\DataTransferObjects\PatientReportData;
use Illuminate\Contracts\Support\Arrayable;

class PatientReportViewModel implements Arrayable
{
    private PatientReportData $reportData;

    public function __construct(
        private Carbon $start,
        private Carbon $end,
        GeneratePatientReportAction $action,
    ) {
        $this->reportData = $action->execute($this->start, $this->end);
    }

    public function reportData(): PatientReportData
    {
        return $this->reportData;
    }

    /**
     * @return array{start: string, end: string}
     */
    public function dateRange(): array
    {
        return [
            'start' => $this->start->toDateString(),
            'end' => $this->end->toDateString(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'date_range' => $this->dateRange(),
            'total_admitted' => $this->reportData->total_admitted,
            'total_discharged' => $this->reportData->total_discharged,
            'patients' => $this->reportData->patients,
        ];
    }
}
