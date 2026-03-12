<?php

namespace Domain\Inquiries\States;

class RejectedInquiryState extends InquiryState
{
    public function colour(): string
    {
        return 'red';
    }

    public function canTransition(): bool
    {
        return false;
    }
}
