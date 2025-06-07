<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'gender',
        'civil_status',
        'contact_number',
        'email',
        'address',
        'occupation',
        'emergency_contact_name',
        'emergency_contact_number',
        'status',
        'photo',
        'valid_id',
        'reference_number',
        'registration_type',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Accessor for full name
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
    }

    // Accessor for age
    public function getAgeAttribute()
    {
        return (int)$this->birth_date->diffInYears(now());
    }
}
