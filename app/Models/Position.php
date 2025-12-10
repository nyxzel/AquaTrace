<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';
    protected $primaryKey = 'position_id';

    // IMPORTANT: Set to false since we're manually managing recorded_at
    public $timestamps = false;

    protected $fillable = [
        'vessel_id',
        'voyage_id',
        'latitude',
        'longitude',
        'speed',
        'recorded_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'speed' => 'decimal:2',
        'recorded_at' => 'datetime'
    ];

    /**
     * Get the vessel that owns this position
     */
    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'vessel_id', 'vessel_id');
    }

    /**
     * Get the voyage that owns this position
     */
    public function voyage()
    {
        return $this->belongsTo(Voyage::class, 'voyage_id', 'voyage_id');
    }

    /**
     * Scope for positions within a date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }

    /**
     * Scope for positions for a specific vessel
     */
    public function scopeForVessel($query, $vesselId)
    {
        return $query->where('vessel_id', $vesselId);
    }

    /**
     * Scope for positions for a specific voyage
     */
    public function scopeForVoyage($query, $voyageId)
    {
        return $query->where('voyage_id', $voyageId);
    }

    /**
     * Get the status based on speed
     */
    public function getStatusAttribute()
    {
        if ($this->speed > 15) {
            return 'Active';
        } elseif ($this->speed > 0) {
            return 'In Transit';
        }
        return 'Docked';
    }

    /**
     * Get formatted position data
     */
    public function getFormattedData()
    {
        return [
            'position_id' => $this->position_id,
            'latitude' => number_format($this->latitude, 6),
            'longitude' => number_format($this->longitude, 6),
            'speed' => number_format($this->speed, 2),
            'recorded_at' => $this->recorded_at->format('M d, Y h:i A'),
            'vessel' => $this->vessel ? $this->vessel->name : 'Unknown',
            'voyage_id' => $this->voyage_id,
            'status' => $this->status
        ];
    }
}
