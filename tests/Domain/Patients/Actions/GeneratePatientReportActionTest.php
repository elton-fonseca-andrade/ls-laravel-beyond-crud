<?php

namespace Tests\Domain\Patients\Actions;

use Carbon\Carbon;
use Domain\Patients\Actions\GeneratePatientReportAction;
use Domain\Patients\DataTransferObjects\PatientReportData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\Factories\PatientFactory;
use Tests\TestCase;

class GeneratePatientReportActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_report_data(): void
    {
        $inquiry = InquiryFactory::new()->create();

        PatientFactory::new()
            ->forInquiry($inquiry)
            ->admittedAt(Carbon::make('2025-06-01'))
            ->create();

        $action = app(GeneratePatientReportAction::class);
        $report = $action->execute(
            Carbon::make('2025-01-01'),
            Carbon::make('2025-12-31'),
        );

        $this->assertInstanceOf(PatientReportData::class, $report);
        $this->assertEquals(1, $report->total_admitted);
        $this->assertEquals(0, $report->total_discharged);
        $this->assertCount(1, $report->patients);
    }

    public function test_it_filters_by_date_range(): void
    {
        $inquiry1 = InquiryFactory::new()->create();
        $inquiry2 = InquiryFactory::new()->create();

        PatientFactory::new()
            ->forInquiry($inquiry1)
            ->admittedAt(Carbon::make('2025-03-01'))
            ->create();

        PatientFactory::new()
            ->forInquiry($inquiry2)
            ->admittedAt(Carbon::make('2025-09-01'))
            ->create();

        $action = app(GeneratePatientReportAction::class);
        $report = $action->execute(
            Carbon::make('2025-01-01'),
            Carbon::make('2025-06-30'),
        );

        $this->assertCount(1, $report->patients);
    }

    public function test_it_counts_discharged_patients(): void
    {
        $inquiry = InquiryFactory::new()->create();

        PatientFactory::new()
            ->forInquiry($inquiry)
            ->admittedAt(Carbon::make('2025-06-01'))
            ->discharged(Carbon::make('2025-07-01'))
            ->create();

        $action = app(GeneratePatientReportAction::class);
        $report = $action->execute(
            Carbon::make('2025-01-01'),
            Carbon::make('2025-12-31'),
        );

        $this->assertEquals(0, $report->total_admitted);
        $this->assertEquals(1, $report->total_discharged);
    }
}
