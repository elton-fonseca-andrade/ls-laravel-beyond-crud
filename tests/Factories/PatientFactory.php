<?php

namespace Tests\Factories;

use Carbon\Carbon;
use Domain\Inquiries\Models\Inquiry;
use Domain\Patients\Models\Patient;

class PatientFactory
{
    private static int $number = 0;

    private ?Inquiry $inquiry = null;

    private ?Carbon $admittedAt = null;

    private ?Carbon $dischargedAt = null;

    public static function new(): self
    {
        return new self;
    }

    public function forInquiry(Inquiry $inquiry): self
    {
        $clone = clone $this;
        $clone->inquiry = $inquiry;

        return $clone;
    }

    public function admittedAt(Carbon $date): self
    {
        $clone = clone $this;
        $clone->admittedAt = $date;

        return $clone;
    }

    public function discharged(?Carbon $dischargedAt = null): self
    {
        $clone = clone $this;
        $clone->dischargedAt = $dischargedAt ?? now();

        return $clone;
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public function create(array $extra = []): Patient
    {
        self::$number += 1;

        $inquiry = $this->inquiry ?? InquiryFactory::new()->admitted()->create();

        return Patient::create(array_merge([
            'inquiry_id' => $inquiry->id,
            'name' => $inquiry->name,
            'email' => $inquiry->email,
            'phone' => $inquiry->phone,
            'date_of_birth' => $inquiry->date_of_birth,
            'admitted_at' => $this->admittedAt ?? now(),
            'discharged_at' => $this->dischargedAt,
        ], $extra));
    }
}
