<?php

namespace Domain\Inquiries\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class InquiryState extends State
{
    abstract public function colour(): string;

    abstract public function canTransition(): bool;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingInquiryState::class)
            ->allowTransition(PendingInquiryState::class, AdmittedInquiryState::class, Transitions\PendingToAdmittedTransition::class)
            ->allowTransition(PendingInquiryState::class, RejectedInquiryState::class, Transitions\PendingToRejectedTransition::class);
    }
}
