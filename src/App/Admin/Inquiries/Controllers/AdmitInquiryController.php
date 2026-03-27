<?php

namespace App\Admin\Inquiries\Controllers;

use Domain\Inquiries\Actions\AdmitInquiryAction;
use Domain\Inquiries\DataTransferObjects\InquiryData;
use Domain\Inquiries\Models\Inquiry;
use Illuminate\Http\JsonResponse;

class AdmitInquiryController
{
    public function __invoke(Inquiry $inquiry, AdmitInquiryAction $action): JsonResponse
    {
        $inquiry = $action->execute($inquiry);

        return response()->json([
            'data' => InquiryData::from($inquiry),
        ]);
    }
}
