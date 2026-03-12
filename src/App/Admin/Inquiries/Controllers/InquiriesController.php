<?php

namespace App\Admin\Inquiries\Controllers;

use App\Admin\Inquiries\Queries\InquiryIndexQuery;
use App\Admin\Inquiries\Requests\InquiryRequest;
use App\Admin\Inquiries\Resources\InquiryResource;
use Carbon\Carbon;
use Domain\Inquiries\Actions\CreateInquiryAction;
use Domain\Inquiries\DataTransferObjects\InquiryData;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InquiriesController
{
    public function index(InquiryIndexQuery $query): AnonymousResourceCollection
    {
        return InquiryResource::collection($query->get());
    }

    public function store(InquiryRequest $request, CreateInquiryAction $action): InquiryResource
    {
        $data = new InquiryData(
            name: $request->validated('name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            date_of_birth: Carbon::make($request->validated('date_of_birth')),
            reason: $request->validated('reason'),
            notes: $request->validated('notes'),
        );

        $inquiry = $action->execute($data);

        return new InquiryResource($inquiry);
    }
}
