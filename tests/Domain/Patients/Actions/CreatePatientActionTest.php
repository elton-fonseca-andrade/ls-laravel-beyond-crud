<?php

namespace Tests\Domain\Patients\Actions;

use Domain\Patients\Actions\CreatePatientAction;
use Domain\Patients\DataTransferObjects\PatientData;
use Domain\Patients\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class CreatePatientActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_patient(): void
    {
        $inquiry = InquiryFactory::new()->create();

        $data = new PatientData(
            inquiry_id: $inquiry->id,
            name: $inquiry->name,
            email: $inquiry->email,
            phone: $inquiry->phone,
            date_of_birth: $inquiry->date_of_birth,
        );

        $action = app(CreatePatientAction::class);
        $patient = $action->execute($data);

        $this->assertInstanceOf(Patient::class, $patient);
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'inquiry_id' => $inquiry->id,
            'name' => $inquiry->name,
        ]);
        $this->assertNotNull($patient->admitted_at);
        $this->assertNull($patient->discharged_at);
    }
}
