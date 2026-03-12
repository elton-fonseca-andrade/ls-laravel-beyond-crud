<?php

namespace Domain\Inquiries\Actions;

use Domain\Inquiries\Exceptions\InvalidInquiryTransitionException;
use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\Transitions\PendingToRejectedTransition;

class RejectInquiryAction
{
    public function execute(Inquiry $inquiry, string $reason): Inquiry
    {
        if (! $inquiry->state->canTransition()) {
            throw InvalidInquiryTransitionException::cannotTransition($inquiry);
        }

        $inquiry->state->transition(new PendingToRejectedTransition($inquiry, $reason));
        $inquiry->refresh();

        return $inquiry;
    }
}
