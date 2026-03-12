<?php

namespace Tests\Domain\Inquiries\QueryBuilders;

use Domain\Inquiries\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\InquiryFactory;
use Tests\TestCase;

class InquiryQueryBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_pending_inquiries(): void
    {
        InquiryFactory::new()->create();
        InquiryFactory::new()->admitted()->create();
        InquiryFactory::new()->rejected()->create();

        $pending = Inquiry::query()->wherePending()->get();

        $this->assertCount(1, $pending);
    }

    public function test_it_filters_admitted_inquiries(): void
    {
        InquiryFactory::new()->create();
        InquiryFactory::new()->admitted()->create();

        $admitted = Inquiry::query()->whereAdmitted()->get();

        $this->assertCount(1, $admitted);
    }

    public function test_it_filters_by_created_between(): void
    {
        $inquiry1 = InquiryFactory::new()->create();
        $inquiry1->forceFill(['created_at' => '2025-01-15'])->save();

        $inquiry2 = InquiryFactory::new()->create();
        $inquiry2->forceFill(['created_at' => '2025-03-15'])->save();

        $inquiry3 = InquiryFactory::new()->create();
        $inquiry3->forceFill(['created_at' => '2025-06-15'])->save();

        $results = Inquiry::query()
            ->whereCreatedBetween(
                now()->parse('2025-01-01'),
                now()->parse('2025-04-01'),
            )
            ->get();

        $this->assertCount(2, $results);
    }
}
