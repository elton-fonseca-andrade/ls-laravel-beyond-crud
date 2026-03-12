<?php

namespace Tests\App\Admin\Inquiries\Controllers;

use Domain\Inquiries\Models\Inquiry;
use Domain\Inquiries\States\PendingInquiryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class InquiriesControllerTest extends TestCase
{
    use RefreshDatabase;

    // --- INDEX ---

    public function test_index_returns_all_inquiries(): void
    {
        InquiryFactory::new()->create();
        InquiryFactory::new()->admitted()->create();
        InquiryFactory::new()->rejected()->create();

        $response = $this->getJson(route('admin.inquiries.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    public function test_index_filters_by_state(): void
    {
        InquiryFactory::new()->create();
        InquiryFactory::new()->create();
        InquiryFactory::new()->admitted()->create();

        $response = $this->getJson(route('admin.inquiries.index', [
            'filter' => ['state' => PendingInquiryState::class],
        ]));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_index_filters_by_name(): void
    {
        InquiryFactory::new()->create(['name' => 'Alice Johnson']);
        InquiryFactory::new()->create(['name' => 'Bob Smith']);

        $response = $this->getJson(route('admin.inquiries.index', [
            'filter' => ['name' => 'Alice'],
        ]));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Alice Johnson');
    }

    public function test_index_sorts_by_name_ascending(): void
    {
        InquiryFactory::new()->create(['name' => 'Zara']);
        InquiryFactory::new()->create(['name' => 'Anna']);

        $response = $this->getJson(route('admin.inquiries.index', [
            'sort' => 'name',
        ]));

        $response->assertOk();
        $response->assertJsonPath('data.0.name', 'Anna');
        $response->assertJsonPath('data.1.name', 'Zara');
    }

    public function test_index_sorts_by_created_at_descending_by_default(): void
    {
        $older = InquiryFactory::new()->create();
        $older->forceFill(['created_at' => '2025-01-01'])->save();

        $newer = InquiryFactory::new()->create();
        $newer->forceFill(['created_at' => '2025-06-01'])->save();

        $response = $this->getJson(route('admin.inquiries.index'));

        $response->assertOk();
        $response->assertJsonPath('data.0.id', $newer->id);
        $response->assertJsonPath('data.1.id', $older->id);
    }

    public function test_index_returns_correct_resource_structure(): void
    {
        InquiryFactory::new()->create();

        $response = $this->getJson(route('admin.inquiries.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'date_of_birth',
                    'reason',
                    'notes',
                    'state',
                    'state_colour',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_index_returns_empty_collection_when_no_inquiries(): void
    {
        $response = $this->getJson(route('admin.inquiries.index'));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    // --- STORE ---

    public function test_store_creates_inquiry_with_valid_data(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1-555-0001',
            'date_of_birth' => '1990-05-15',
            'reason' => 'Anxiety management',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'John Doe');
        $response->assertJsonPath('data.email', 'john@example.com');
        $response->assertJsonPath('data.state', PendingInquiryState::class);

        $this->assertDatabaseHas('inquiries', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_store_creates_inquiry_with_optional_notes(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+1-555-0002',
            'date_of_birth' => '1985-03-20',
            'reason' => 'Depression',
            'notes' => 'Referred by GP',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.notes', 'Referred by GP');
    }

    public function test_store_fails_when_name_is_missing(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), [
            'email' => 'john@example.com',
            'phone' => '+1-555-0001',
            'date_of_birth' => '1990-05-15',
            'reason' => 'Anxiety',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_store_fails_when_email_is_invalid(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'phone' => '+1-555-0001',
            'date_of_birth' => '1990-05-15',
            'reason' => 'Anxiety',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_store_fails_when_date_of_birth_is_in_the_future(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1-555-0001',
            'date_of_birth' => now()->addYear()->toDateString(),
            'reason' => 'Anxiety',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['date_of_birth']);
    }

    public function test_store_fails_when_reason_is_missing(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1-555-0001',
            'date_of_birth' => '1990-05-15',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['reason']);
    }

    public function test_store_fails_when_all_required_fields_are_missing(): void
    {
        $response = $this->postJson(route('admin.inquiries.store'), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name', 'email', 'phone', 'date_of_birth', 'reason']);
    }
}
