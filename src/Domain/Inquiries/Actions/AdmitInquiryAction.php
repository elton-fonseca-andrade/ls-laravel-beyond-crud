<?php

namespace Domain\Inquiries\Actions;

use Domain\Inquiries\Events\InquiryAdmittedEvent;
use Domain\Inquiries\Exceptions\InvalidInquiryTransitionException;
use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\Transitions\PendingToAdmittedTransition;
use Domain\Patients\Actions\CreatePatientAction;
use Domain\Patients\DataTransferObjects\PatientData;

class AdmitInquiryAction
{
    public function __construct(
        private CreatePatientAction $createPatientAction,
    ) {}

    public function execute(Inquiry $inquiry): Inquiry
    {
        if (! $inquiry->state->canTransition()) {
            throw InvalidInquiryTransitionException::cannotTransition($inquiry);
        }

        $inquiry->state->transition(new PendingToAdmittedTransition($inquiry));
        $inquiry->refresh();

        $patient = $this->createPatientAction->execute(new PatientData(
            inquiry_id: $inquiry->id,
            name: $inquiry->name,
            email: $inquiry->email,
            phone: $inquiry->phone,
            date_of_birth: $inquiry->date_of_birth,
        ));

        event(new InquiryAdmittedEvent($inquiry, $patient));

        return $inquiry;
    }
}
