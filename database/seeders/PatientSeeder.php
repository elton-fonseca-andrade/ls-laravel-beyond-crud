<?php

namespace Database\Seeders;

use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\AdmittedInquiryState;
use Domain\Patients\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $admittedInquiries = Inquiry::query()->whereAdmitted()->get();

        $admissionDates = [
            '2025-10-15 09:00:00',
            '2025-11-02 14:30:00',
            '2025-12-10 10:00:00',
            '2026-01-20 11:15:00',
        ];

        foreach ($admittedInquiries as $index => $inquiry) {
            $admittedAt = $admissionDates[$index] ?? now()->subDays(rand(1, 90))->toDateTimeString();

            $patient = Patient::create([
                'inquiry_id' => $inquiry->id,
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'phone' => $inquiry->phone,
                'date_of_birth' => $inquiry->date_of_birth,
                'admitted_at' => $admittedAt,
                'discharged_at' => null,
            ]);

            // Discharge the first patient as an example
            if ($index === 0) {
                $patient->update([
                    'discharged_at' => '2026-01-05 16:00:00',
                ]);
            }
        }
    }
}
