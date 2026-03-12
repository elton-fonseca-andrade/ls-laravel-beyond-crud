<?php

namespace Tests\Domain\Patients\Collections;

use Domain\Patients\Collections\PatientCollection;
use Domain\Patients\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\Factories\PatientFactory;
use Tests\TestCase;

class PatientCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_patients_filters_non_discharged(): void
    {
        $inquiry1 = InquiryFactory::new()->create();
        $inquiry2 = InquiryFactory::new()->create();

        $active = PatientFactory::new()->forInquiry($inquiry1)->create();
        $discharged = PatientFactory::new()->forInquiry($inquiry2)->discharged()->create();

        $collection = Patient::all();

        $this->assertInstanceOf(PatientCollection::class, $collection);
        $this->assertCount(1, $collection->activePatients());
        $this->assertEquals($active->id, $collection->activePatients()->first()->id);
    }

    public function test_discharged_patients_filters_discharged(): void
    {
        $inquiry1 = InquiryFactory::new()->create();
        $inquiry2 = InquiryFactory::new()->create();

        PatientFactory::new()->forInquiry($inquiry1)->create();
        $discharged = PatientFactory::new()->forInquiry($inquiry2)->discharged()->create();

        $collection = Patient::all();

        $this->assertCount(1, $collection->dischargedPatients());
        $this->assertEquals($discharged->id, $collection->dischargedPatients()->first()->id);
    }
}
