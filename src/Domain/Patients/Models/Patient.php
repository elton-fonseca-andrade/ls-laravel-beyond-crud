<?php

namespace Domain\Patients\Models;

use Domain\Inquiries\Models\Inquiry;
use Domain\Patients\Collections\PatientCollection;
use Domain\Patients\QueryBuilders\PatientQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'inquiry_id',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'admitted_at',
        'discharged_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admitted_at' => 'datetime',
            'discharged_at' => 'datetime',
        ];
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function newEloquentBuilder($query): PatientQueryBuilder
    {
        return new PatientQueryBuilder($query);
    }

    public function newCollection(array $models = []): PatientCollection
    {
        return new PatientCollection($models);
    }
}
