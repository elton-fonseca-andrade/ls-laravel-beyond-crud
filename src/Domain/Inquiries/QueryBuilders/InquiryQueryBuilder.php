<?php

namespace Domain\Inquiries\QueryBuilders;

use Carbon\Carbon;
use Domain\Inquiries\States\AdmittedInquiryState;
use Domain\Inquiries\States\PendingInquiryState;
use Illuminate\Database\Eloquent\Builder;

class InquiryQueryBuilder extends Builder
{
    public function wherePending(): self
    {
        return $this->whereState('state', PendingInquiryState::class);
    }

    public function whereAdmitted(): self
    {
        return $this->whereState('state', AdmittedInquiryState::class);
    }

    public function whereCreatedBetween(Carbon $start, Carbon $end): self
    {
        return $this->whereBetween('created_at', [$start, $end]);
    }
}
