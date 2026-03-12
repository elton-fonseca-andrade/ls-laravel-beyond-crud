<?php

namespace Tests\App\Console;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\Factories\PatientFactory;
use Tests\TestCase;

class GeneratePatientReportCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_outputs_report_table(): void
    {
        $inquiry = InquiryFactory::new()->create();

        PatientFactory::new()
            ->forInquiry($inquiry)
            ->admittedAt(Carbon::make('2025-06-01'))
            ->create();

        $this->artisan('patients:report', [
            '--from' => '2025-01-01',
            '--to' => '2025-12-31',
        ])
            ->expectsOutputToContain('Total Admitted: 1')
            ->expectsOutputToContain('Total Discharged: 0')
            ->assertSuccessful();
    }

    public function test_it_handles_empty_report(): void
    {
        $this->artisan('patients:report', [
            '--from' => '2025-01-01',
            '--to' => '2025-12-31',
        ])
            ->expectsOutputToContain('Total Admitted: 0')
            ->assertSuccessful();
    }
}
