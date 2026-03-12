<?php

namespace Domain\Inquiries\Actions;

use Domain\Inquiries\DataTransferObjects\InquiryData;
use Domain\Inquiries\Models\Inquiry;

class CreateInquiryAction
{
    public function execute(InquiryData $data): Inquiry
    {
        return Inquiry::create([
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'date_of_birth' => $data->date_of_birth,
            'reason' => $data->reason,
            'notes' => $data->notes,
        ]);
    }
}
