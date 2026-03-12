<?php

namespace Domain\Inquiries\States\Transitions;

use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\AdmittedInquiryState;
use Spatie\ModelStates\Transition;

class PendingToAdmittedTransition extends Transition
{
    public function __construct(
        private Inquiry $inquiry,
    ) {}

    public function handle(): Inquiry
    {
        $this->inquiry->state = new AdmittedInquiryState($this->inquiry);
        $this->inquiry->save();

        return $this->inquiry;
    }
}
