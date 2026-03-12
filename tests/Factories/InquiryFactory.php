<?php

namespace Tests\Factories;

use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\AdmittedInquiryState;
use Domain\Inquiries\States\PendingInquiryState;
use Domain\Inquiries\States\RejectedInquiryState;

class InquiryFactory
{
    private static int $number = 0;

    private ?string $state = null;

    private ?string $rejectionReason = null;

    private ?PatientFactory $patientFactory = null;

    public static function new(): self
    {
        return new self;
    }

    public function admitted(?PatientFactory $patientFactory = null): self
    {
        $clone = clone $this;
        $clone->state = AdmittedInquiryState::class;
        $clone->patientFactory = $patientFactory ?? PatientFactory::new();

        return $clone;
    }

    public function rejected(string $reason = 'No capacity'): self
    {
        $clone = clone $this;
        $clone->state = RejectedInquiryState::class;
        $clone->rejectionReason = $reason;

        return $clone;
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public function create(array $extra = []): Inquiry
    {
        self::$number += 1;

        $inquiry = Inquiry::create(array_merge([
            'name' => 'Inquiry '.self::$number,
            'email' => 'inquiry'.self::$number.'@example.com',
            'phone' => '+1-555-000-'.str_pad((string) self::$number, 4, '0', STR_PAD_LEFT),
            'date_of_birth' => '1990-01-01',
            'reason' => 'Seeking mental health support',
            'notes' => $this->rejectionReason,
            'state' => $this->state ?? PendingInquiryState::class,
        ], $extra));

        if ($this->patientFactory) {
            $this->patientFactory->forInquiry($inquiry)->create();
        }

        return $inquiry;
    }
}
