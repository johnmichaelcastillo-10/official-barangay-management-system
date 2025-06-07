<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'document_type',
        'purpose',
        'status',
        'remarks',
        'fee_amount',
        'payment_status',
        'requested_date',
        'target_release_date',
        'actual_release_date',
        'tracking_number',
        'processed_by'
    ];

    protected $casts = [
        'requested_date' => 'date',
        'target_release_date' => 'date',
        'actual_release_date' => 'date',
        'fee_amount' => 'decimal:2'
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Generate unique tracking number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tracking_number = 'BR-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}
