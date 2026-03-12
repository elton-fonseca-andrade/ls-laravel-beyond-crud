<?php

namespace App\Admin\Inquiries\Controllers;

use App\Admin\Inquiries\Resources\InquiryResource;
use Domain\Inquiries\Actions\AdmitInquiryAction;
use Domain\Inquiries\Models\Inquiry;

class AdmitInquiryController
{
    public function __invoke(Inquiry $inquiry, AdmitInquiryAction $action): InquiryResource
    {
        $inquiry = $action->execute($inquiry);

        return new InquiryResource($inquiry);
    }
}
