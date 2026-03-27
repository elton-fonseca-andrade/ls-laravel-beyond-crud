<?php

namespace Domain\Inquiries\DataTransferObjects;

use Carbon\Carbon;
use Domain\Inquiries\Models\Inquiry;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class InquiryData extends Data
{
    public function __construct(
        #[Max(255)]
        public readonly string $name,
        #[Email, Max(255)]
        public readonly string $email,
        #[Max(50)]
        public readonly string $phone,
        #[Before('today'), WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon $date_of_birth,
        public readonly string $reason,
        public readonly ?string $notes = null,
        public readonly ?int $id = null,
        public readonly ?string $state = null,
        public readonly ?string $state_colour = null,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null,
    ) {}

    // public static function rules(): array
    // {
    //     return [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'email', 'max:255'],
    //         'phone' => ['required', 'string', 'max:50'],
    //         'date_of_birth' => ['required', 'date', 'before:today'],
    //         'reason' => ['required', 'string'],
    //         'notes' => ['nullable', 'string'],
    //     ];
    // }

    public static function fromInquiry(Inquiry $inquiry): self
    {
        return new self(
            id: $inquiry->id,
            name: $inquiry->name,
            email: $inquiry->email,
            phone: $inquiry->phone,
            date_of_birth: $inquiry->date_of_birth,
            reason: $inquiry->reason,
            notes: $inquiry->notes,
            state: $inquiry->state::class,
            state_colour: $inquiry->state->colour(),
            created_at: $inquiry->created_at->toIso8601String(),
            updated_at: $inquiry->updated_at->toIso8601String(),
        );
    }
}
