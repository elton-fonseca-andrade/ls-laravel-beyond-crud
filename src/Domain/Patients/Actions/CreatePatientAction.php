<?php

namespace Domain\Patients\Actions;

use Domain\Patients\DataTransferObjects\PatientData;
use Domain\Patients\Models\Patient;

class CreatePatientAction
{
    public function execute(PatientData $data): Patient
    {
        return Patient::create([
            'inquiry_id' => $data->inquiry_id,
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'date_of_birth' => $data->date_of_birth,
            'admitted_at' => now(),
        ]);
    }
}
