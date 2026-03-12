<?php

namespace Tests\Domain\Inquiries\Actions;

use Domain\Inquiries\Actions\RejectInquiryAction;
use Domain\Inquiries\Exceptions\InvalidInquiryTransitionException;
use Domain\Inquiries\States\RejectedInquiryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class RejectInquiryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_a_pending_inquiry(): void
    {
        $inquiry = InquiryFactory::new()->create();

        $action = app(RejectInquiryAction::class);
        $result = $action->execute($inquiry, 'No capacity');

        $this->assertInstanceOf(RejectedInquiryState::class, $result->state);
        $this->assertEquals('No capacity', $result->notes);
    }

    public function test_it_throws_when_inquiry_is_already_admitted(): void
    {
        $this->expectException(InvalidInquiryTransitionException::class);

        $inquiry = InquiryFactory::new()->admitted()->create();

        $action = app(RejectInquiryAction::class);
        $action->execute($inquiry, 'Too late');
    }
}
