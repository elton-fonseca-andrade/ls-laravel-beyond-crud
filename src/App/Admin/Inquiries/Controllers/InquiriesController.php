<?php

namespace App\Admin\Inquiries\Controllers;

use App\Admin\Inquiries\Queries\InquiryIndexQuery;
use Domain\Inquiries\Actions\CreateInquiryAction;
use Domain\Inquiries\DataTransferObjects\InquiryData;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\DataCollection;

class InquiriesController
{
    public function index(InquiryIndexQuery $query): DataCollection
    {
        return InquiryData::collect($query->get(), DataCollection::class)
            ->wrap('data');
    }

    public function store(InquiryData $data, CreateInquiryAction $action): JsonResponse
    {
        $inquiry = $action->execute($data);

        return InquiryData::from($inquiry)
            ->wrap('data')
            ->toResponse(request());
    }
}
