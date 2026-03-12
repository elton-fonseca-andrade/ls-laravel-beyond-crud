<?php

namespace Tests\Domain\Inquiries\States;

use Domain\Inquiries\States\AdmittedInquiryState;
use Domain\Inquiries\States\PendingInquiryState;
use Domain\Inquiries\States\RejectedInquiryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class InquiryStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_state_colour_is_orange(): void
    {
        $inquiry = InquiryFactory::new()->create();

        $this->assertEquals('orange', $inquiry->state->colour());
    }

    public function test_pending_state_can_transition(): void
    {
        $inquiry = InquiryFactory::new()->create();

        $this->assertTrue($inquiry->state->canTransition());
    }

    public function test_admitted_state_colour_is_green(): void
    {
        $inquiry = InquiryFactory::new()->admitted()->create();

        $this->assertEquals('green', $inquiry->state->colour());
    }

    public function test_admitted_state_cannot_transition(): void
    {
        $inquiry = InquiryFactory::new()->admitted()->create();

        $this->assertFalse($inquiry->state->canTransition());
    }

    public function test_rejected_state_colour_is_red(): void
    {
        $inquiry = InquiryFactory::new()->rejected()->create();

        $this->assertEquals('red', $inquiry->state->colour());
    }

    public function test_rejected_state_cannot_transition(): void
    {
        $inquiry = InquiryFactory::new()->rejected()->create();

        $this->assertFalse($inquiry->state->canTransition());
    }

    public function test_pending_state_is_correct_class(): void
    {
        $inquiry = InquiryFactory::new()->create();

        $this->assertInstanceOf(PendingInquiryState::class, $inquiry->state);
    }

    public function test_admitted_state_is_correct_class(): void
    {
        $inquiry = InquiryFactory::new()->admitted()->create();

        $this->assertInstanceOf(AdmittedInquiryState::class, $inquiry->state);
    }

    public function test_rejected_state_is_correct_class(): void
    {
        $inquiry = InquiryFactory::new()->rejected()->create();

        $this->assertInstanceOf(RejectedInquiryState::class, $inquiry->state);
    }
}
