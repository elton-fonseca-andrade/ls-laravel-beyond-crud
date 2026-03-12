<?php

namespace Domain\Inquiries\DataTransferObjects;

use Carbon\Carbon;

class InquiryData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly Carbon $date_of_birth,
        public readonly string $reason,
        public readonly ?string $notes = null,
    ) {}
}
