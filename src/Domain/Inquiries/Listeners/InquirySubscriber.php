<?php

namespace Domain\Inquiries\Listeners;

use Domain\Inquiries\Events\InquiryAdmittedEvent;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class InquirySubscriber
{
    public function handleInquiryAdmitted(InquiryAdmittedEvent $event): void
    {
        Log::info('Inquiry admitted', [
            'inquiry_id' => $event->inquiry->id,
            'patient_id' => $event->patient->id,
            'name' => $event->inquiry->name,
        ]);
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            InquiryAdmittedEvent::class,
            [self::class, 'handleInquiryAdmitted'],
        );
    }
}
