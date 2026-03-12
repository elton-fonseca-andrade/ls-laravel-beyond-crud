<?php

namespace Domain\Patients\Collections;

use Domain\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientCollection extends Collection
{
    public function activePatients(): self
    {
        return $this->filter(fn (Patient $patient) => $patient->discharged_at === null);
    }

    public function dischargedPatients(): self
    {
        return $this->filter(fn (Patient $patient) => $patient->discharged_at !== null);
    }
}
