<?php

namespace Tests\Domain\Inquiries\Actions;

use Carbon\Carbon;
use Domain\Inquiries\Actions\CreateInquiryAction;
use Domain\Inquiries\DataTransferObjects\InquiryData;
use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\PendingInquiryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateInquiryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_inquiry_with_pending_state(): void
    {
        $data = new InquiryData(
            name: 'John Doe',
            email: 'john@example.com',
            phone: '+1-555-000-0001',
            date_of_birth: Carbon::make('1990-05-15'),
            reason: 'Seeking therapy',
        );

        $action = app(CreateInquiryAction::class);
        $inquiry = $action->execute($data);

        $this->assertInstanceOf(Inquiry::class, $inquiry);
        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1-555-000-0001',
            'reason' => 'Seeking therapy',
        ]);
        $this->assertInstanceOf(PendingInquiryState::class, $inquiry->state);
        $this->assertNull($inquiry->notes);
    }

    public function test_it_creates_an_inquiry_with_notes(): void
    {
        $data = new InquiryData(
            name: 'Jane Doe',
            email: 'jane@example.com',
            phone: '+1-555-000-0002',
            date_of_birth: Carbon::make('1985-03-20'),
            reason: 'Anxiety management',
            notes: 'Referred by GP',
        );

        $action = app(CreateInquiryAction::class);
        $inquiry = $action->execute($data);

        $this->assertEquals('Referred by GP', $inquiry->notes);
    }
}
