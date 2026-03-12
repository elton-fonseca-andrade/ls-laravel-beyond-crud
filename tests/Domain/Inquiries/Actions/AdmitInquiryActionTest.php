<?php

namespace Tests\Domain\Inquiries\Actions;

use Domain\Inquiries\Actions\AdmitInquiryAction;
use Domain\Inquiries\Events\InquiryAdmittedEvent;
use Domain\Inquiries\Exceptions\InvalidInquiryTransitionException;
use Domain\Inquiries\States\AdmittedInquiryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class AdmitInquiryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_admits_a_pending_inquiry(): void
    {
        Event::fake([InquiryAdmittedEvent::class]);

        $inquiry = InquiryFactory::new()->create();

        $action = app(AdmitInquiryAction::class);
        $result = $action->execute($inquiry);

        $this->assertInstanceOf(AdmittedInquiryState::class, $result->state);
    }

    public function test_it_creates_a_patient_when_admitting(): void
    {
        Event::fake([InquiryAdmittedEvent::class]);

        $inquiry = InquiryFactory::new()->create();

        $action = app(AdmitInquiryAction::class);
        $action->execute($inquiry);

        $this->assertDatabaseHas('patients', [
            'inquiry_id' => $inquiry->id,
            'name' => $inquiry->name,
            'email' => $inquiry->email,
        ]);
    }

    public function test_it_dispatches_inquiry_admitted_event(): void
    {
        Event::fake([InquiryAdmittedEvent::class]);

        $inquiry = InquiryFactory::new()->create();

        $action = app(AdmitInquiryAction::class);
        $action->execute($inquiry);

        Event::assertDispatched(InquiryAdmittedEvent::class, function ($event) use ($inquiry) {
            return $event->inquiry->id === $inquiry->id
                && $event->patient->inquiry_id === $inquiry->id;
        });
    }

    public function test_it_throws_when_inquiry_is_not_pending(): void
    {
        $this->expectException(InvalidInquiryTransitionException::class);

        $inquiry = InquiryFactory::new()->rejected()->create();

        $action = app(AdmitInquiryAction::class);
        $action->execute($inquiry);
    }
}
