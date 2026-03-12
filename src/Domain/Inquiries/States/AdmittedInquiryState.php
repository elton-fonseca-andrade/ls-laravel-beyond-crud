<?php

namespace Domain\Inquiries\States;

class AdmittedInquiryState extends InquiryState
{
    public function colour(): string
    {
        return 'green';
    }

    public function canTransition(): bool
    {
        return false;
    }
}
