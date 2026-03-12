<?php

namespace Domain\Inquiries\Models;

use Domain\Inquiries\QueryBuilders\InquiryQueryBuilder;
use Domain\Inquiries\States\InquiryState;
use Domain\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\ModelStates\HasStates;

class Inquiry extends Model
{
    use HasStates;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'reason',
        'notes',
        'state',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'state' => InquiryState::class,
        ];
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function newEloquentBuilder($query): InquiryQueryBuilder
    {
        return new InquiryQueryBuilder($query);
    }
}
