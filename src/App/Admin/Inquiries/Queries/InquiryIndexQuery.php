<?php

namespace App\Admin\Inquiries\Queries;

use Domain\Inquiries\Models\Inquiry;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class InquiryIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        parent::__construct(Inquiry::query(), $request);

        $this
            ->allowedFilters([
                AllowedFilter::exact('state'),
                AllowedFilter::partial('name'),
                AllowedFilter::callback('created_after', fn ($query, $value) => $query->where('created_at', '>=', $value)),
                AllowedFilter::callback('created_before', fn ($query, $value) => $query->where('created_at', '<=', $value)),
            ])
            ->allowedSorts([
                AllowedSort::field('name'),
                AllowedSort::field('created_at'),
            ])
            ->defaultSort('-created_at');
    }
}
