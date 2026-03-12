<?php

namespace Domain\Inquiries\Events;

use Domain\Inquiries\Models\Inquiry;
use Domain\Patients\Models\Patient;

class InquiryAdmittedEvent
{
    public function __construct(
        public readonly Inquiry $inquiry,
        public readonly Patient $patient,
    ) {}
}
