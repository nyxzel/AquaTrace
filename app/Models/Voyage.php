<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $table = 'voyage';
    protected $primaryKey = 'voyage_id';

    protected $fillable = [
        'vessel_id',
        'departure_port',
        'arrival_port',
        'departure_date',
        'arrival_date',
        'status'
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Valid voyage statuses
     */
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DELAYED = 'delayed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the vessel that owns this voyage
     */
    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'vessel_id', 'vessel_id');
    }

    /**
     * Get the departure port
     */
    public function departurePort()
    {
        return $this->belongsTo(Port::class, 'departure_port', 'port_id');
    }

    /**
     * Get the arrival port
     */
    public function arrivalPort()
    {
        return $this->belongsTo(Port::class, 'arrival_port', 'port_id');
    }

    /**
     * Scope for active voyages
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for completed voyages
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for scheduled voyages
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    /**
     * Check if voyage is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if voyage is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Mark voyage as started
     */
    public function markAsStarted()
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'departure_date' => now()
        ]);
    }

    /**
     * Mark voyage as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'arrival_date' => now()
        ]);
    }

    /**
     * Mark voyage as delayed
     */
    public function markAsDelayed()
    {
        $this->update(['status' => self::STATUS_DELAYED]);
    }

    /**
     * Get voyage duration in hours
     */
    public function getDurationAttribute()
    {
        if (!$this->departure_date || !$this->arrival_date) {
            return null;
        }

        return $this->departure_date->diffInHours($this->arrival_date);
    }

    /**
     * Get estimated time of arrival
     */
    public function getEtaAttribute()
    {
        if ($this->arrival_date) {
            return $this->arrival_date;
        }

        // Calculate ETA based on current speed and distance if available
        // This is a simplified version - you'd need actual distance calculation
        return null;
    }

    /**
     * Get voyage status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_SCHEDULED => 'info',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_DELAYED => 'warning',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get formatted voyage information
     */
    public function getFormattedInfoAttribute()
    {
        return [
            'id' => $this->voyage_id,
            'vessel' => $this->vessel->name ?? 'Unknown',
            'departure_port' => $this->departurePort->name ?? 'Unknown',
            'arrival_port' => $this->arrivalPort->name ?? 'Unknown',
            'departure_date' => $this->departure_date?->format('M d, Y H:i'),
            'arrival_date' => $this->arrival_date?->format('M d, Y H:i'),
            'status' => ucfirst(str_replace('_', ' ', $this->status)),
            'duration' => $this->duration,
            'status_color' => $this->status_color
        ];
    }
}
