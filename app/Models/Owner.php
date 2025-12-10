<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $table = 'owners';
    protected $primaryKey = 'owner_id';

    protected $fillable = [
        'national_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'dob',
        'nationality',
        'company',
        'job_title',
        'industry',
        'user_id',
        'address_id'
    ];

    protected $casts = [
        'dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user account
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the address
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    /**
     * Get all vessels owned by this owner
     */
    public function vessels()
    {
        return $this->hasMany(Vessel::class, 'owner_id', 'owner_id');
    }

    /**
     * Get active vessels
     */
    public function activeVessels()
    {
        return $this->vessels()->where('is_archived', false);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name
        ]);

        return implode(' ', $parts);
    }

    /**
     * Get formatted owner info
     */
    public function getFormattedInfoAttribute()
    {
        return [
            'owner_id' => $this->owner_id,
            'full_name' => $this->full_name,
            'national_id' => $this->national_id,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'company' => $this->company,
            'job_title' => $this->job_title,
            'address' => $this->address ? $this->address->full_address : 'Not provided',
            'contact' => $this->address->contact ?? 'Not provided',
            'email' => $this->address->email ?? 'Not provided'
        ];
    }
}
