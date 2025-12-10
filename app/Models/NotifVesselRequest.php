<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifVesselRequest extends Model
{
    use SoftDeletes;

    protected $table = 'notif_vessel_request';

    // FIXED: Ensure auto-increment is enabled
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'owner_id',
        'vessel_name',
        'imo_number',
        'mmsi_number',
        'call_sign',
        'vessel_type',
        'flag_state',
        'length_overall',
        'gross_tonnage',
        'year_built',
        'additional_notes',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'length_overall' => 'float',
        'gross_tonnage' => 'float',
        'year_built' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // --- Status Constants ---
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // --- Relationships ---
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    // --- Status Helper Methods ---
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function approve($adminId)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $adminId,
            'rejection_reason' => null,
        ]);
    }

    public function reject($adminId, $reason = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejected_by' => $adminId,
            'rejection_reason' => $reason ?? 'No reason provided',
        ]);
    }

    // --- Scopes ---
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    // --- Accessors ---
    public function getOwnerFullNameAttribute()
    {
        if (!$this->owner) {
            return 'Unknown';
        }

        $middle = $this->owner->middle_name
            ? ' ' . $this->owner->middle_name . ' '
            : ' ';

        return $this->owner->first_name . $middle . $this->owner->last_name;
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    public function getFormattedSubmittedAtAttribute()
    {
        return $this->submitted_at
            ? $this->submitted_at->format('M d, Y h:i A')
            : 'N/A';
    }

    // --- Permission Helpers ---
    public function canBeEditedByUser(): bool
    {
        return $this->isPending();
    }

    public function canBeDeletedByUser(): bool
    {
        return $this->isPending();
    }

    // --- Auto-set Submitted At ---
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (!$request->submitted_at) {
                $request->submitted_at = now();
            }
        });
    }
}
