<?php

namespace Tests\App\Admin\Inquiries\Controllers;

use Domain\Inquiries\Events\InquiryAdmittedEvent;
use Domain\Inquiries\States\AdmittedInquiryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class AdmitInquiryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_admits_an_inquiry_and_returns_updated_resource(): void
    {
        Event::fake([InquiryAdmittedEvent::class]);

        $inquiry = InquiryFactory::new()->create();

        $response = $this->postJson(route('admin.inquiries.admit', $inquiry));

        $response->assertOk();
        $response->assertJsonPath('data.id', $inquiry->id);
        $response->assertJsonPath('data.state', AdmittedInquiryState::class);
        $response->assertJsonPath('data.state_colour', 'green');
    }

    public function test_it_returns_500_when_inquiry_cannot_be_admitted(): void
    {
        $inquiry = InquiryFactory::new()->rejected()->create();

        $response = $this->postJson(route('admin.inquiries.admit', $inquiry));

        $response->assertStatus(500);
    }
}
