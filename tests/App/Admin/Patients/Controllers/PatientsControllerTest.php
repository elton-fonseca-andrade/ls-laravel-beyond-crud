<?php

namespace Tests\App\Admin\Patients\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\Factories\PatientFactory;
use Tests\TestCase;

class PatientsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_report_with_default_date_range(): void
    {
        $inquiry = InquiryFactory::new()->create();

        PatientFactory::new()
            ->forInquiry($inquiry)
            ->admittedAt(Carbon::now()->startOfYear()->addMonth())
            ->create();

        $response = $this->getJson(route('admin.patients.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'date_range' => ['start', 'end'],
            'total_admitted',
            'total_discharged',
            'patients',
        ]);
    }

    public function test_index_returns_correct_counts(): void
    {
        $inquiry1 = InquiryFactory::new()->create();
        $inquiry2 = InquiryFactory::new()->create();

        PatientFactory::new()
            ->forInquiry($inquiry1)
            ->admittedAt(Carbon::make('2025-06-01'))
            ->create();

        PatientFactory::new()
            ->forInquiry($inquiry2)
            ->admittedAt(Carbon::make('2025-07-01'))
            ->discharged(Carbon::make('2025-09-01'))
            ->create();

        $response = $this->getJson(route('admin.patients.index', [
            'start' => '2025-01-01',
            'end' => '2025-12-31',
        ]));

        $response->assertOk();
        $response->assertJsonPath('total_admitted', 1);
        $response->assertJsonPath('total_discharged', 1);
    }

    public function test_index_filters_by_date_range(): void
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

        $response = $this->getJson(route('admin.patients.index', [
            'start' => '2025-01-01',
            'end' => '2025-06-30',
        ]));

        $response->assertOk();
        $response->assertJsonCount(1, 'patients');
    }

    public function test_index_returns_correct_date_range_in_response(): void
    {
        $response = $this->getJson(route('admin.patients.index', [
            'start' => '2025-01-01',
            'end' => '2025-12-31',
        ]));

        $response->assertOk();
        $response->assertJsonPath('date_range.start', '2025-01-01');
        $response->assertJsonPath('date_range.end', '2025-12-31');
    }

    public function test_index_returns_empty_report_when_no_patients(): void
    {
        $response = $this->getJson(route('admin.patients.index', [
            'start' => '2025-01-01',
            'end' => '2025-12-31',
        ]));

        $response->assertOk();
        $response->assertJsonPath('total_admitted', 0);
        $response->assertJsonPath('total_discharged', 0);
        $response->assertJsonCount(0, 'patients');
    }
}
