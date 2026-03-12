<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Domain\Patients\Actions\GeneratePatientReportAction;
use Illuminate\Console\Command;

class GeneratePatientReportCommand extends Command
{
    protected $signature = 'patients:report {--from= : Start date (Y-m-d)} {--to= : End date (Y-m-d)}';

    protected $description = 'Generate a patient report for a given date range';

    public function handle(GeneratePatientReportAction $action): int
    {
        $start = Carbon::parse($this->option('from') ?? now()->startOfYear()->toDateString());
        $end = Carbon::parse($this->option('to') ?? now()->endOfYear()->toDateString());

        $report = $action->execute($start, $end);

        $this->info("Patient Report: {$start->toDateString()} to {$end->toDateString()}");
        $this->newLine();
        $this->info("Total Admitted: {$report->total_admitted}");
        $this->info("Total Discharged: {$report->total_discharged}");
        $this->newLine();

        $this->table(
            ['ID', 'Name', 'Email', 'Admitted At', 'Discharged At'],
            $report->patients->map(fn ($patient) => [
                $patient->id,
                $patient->name,
                $patient->email,
                $patient->admitted_at->toDateString(),
                $patient->discharged_at?->toDateString() ?? '-',
            ])->toArray(),
        );

        return self::SUCCESS;
    }
}
