<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RejectedRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'gender',
        'civil_status',
        'occupation',
        'contact_number',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_number',
        'photo',
        'valid_id',
        'registration_type',
        'submitted_at',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'submitted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the user who rejected this registration
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get age from birth date
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    /**
     * Get status for tracking
     */
    public function getStatusAttribute()
    {
        return 'rejected';
    }

    /**
     * Scope for recent rejections
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('rejected_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for old rejections (for cleanup)
     */
    public function scopeOld($query, $days = 30)
    {
        return $query->where('rejected_at', '<', now()->subDays($days));
    }
}
