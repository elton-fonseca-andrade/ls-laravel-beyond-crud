<?php

namespace Domain\Inquiries\Exceptions;

use Domain\Inquiries\Models\Inquiry;
use Exception;

class InvalidInquiryTransitionException extends Exception
{
    public static function cannotTransition(Inquiry $inquiry): self
    {
        $stateClass = $inquiry->state::class;

        return new self(
            "Cannot transition inquiry #{$inquiry->id} from state {$stateClass}."
        );
    }
}
