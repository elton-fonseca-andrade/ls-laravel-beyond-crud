<?php

namespace Domain\Patients\DataTransferObjects;

use Carbon\Carbon;

class PatientData
{
    public function __construct(
        public readonly int $inquiry_id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly Carbon $date_of_birth,
    ) {}
}
