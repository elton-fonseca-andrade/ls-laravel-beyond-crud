<?php

namespace Domain\Inquiries\States\Transitions;

use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\RejectedInquiryState;
use Spatie\ModelStates\Transition;

class PendingToRejectedTransition extends Transition
{
    public function __construct(
        private Inquiry $inquiry,
        private string $reason,
    ) {}

    public function handle(): Inquiry
    {
        $this->inquiry->state = new RejectedInquiryState($this->inquiry);
        $this->inquiry->notes = $this->reason;
        $this->inquiry->save();

        return $this->inquiry;
    }
}
