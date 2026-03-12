<?php

namespace Domain\Inquiries\States;

class PendingInquiryState extends InquiryState
{
    public function colour(): string
    {
        return 'orange';
    }

    public function canTransition(): bool
    {
        return true;
    }
}
