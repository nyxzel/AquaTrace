<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    use HasFactory;

    protected $table = 'vessels';
    protected $primaryKey = 'vessel_id';

    protected $fillable = [
        'name',
        'type',
        'imo',
        'mmsi',
        'call_sign',
        'flag',
        'LoA',
        'gross_tonnage',
        'year_built',
        'owner_id',
        'admin_id',
        'is_archived',
        'additional_notes'
    ];

    protected $casts = [
        'LoA' => 'decimal:2',
        'gross_tonnage' => 'decimal:2',
        'year_built' => 'integer',
        'is_archived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ----------------------
    // Relationships
    // ----------------------

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id', 'owner_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'vessel_id', 'vessel_id')
            ->orderBy('recorded_at', 'desc');
    }

    public function latestPosition()
    {
        return $this->hasOne(Position::class, 'vessel_id', 'vessel_id')
            ->latest('position_id')  // Order by ID (auto-increment)
            ->latest('recorded_at'); // Fallback to timestamp
    }

    public function voyages()
    {
        return $this->hasMany(Voyage::class, 'vessel_id', 'vessel_id')
            ->orderBy('departure_date', 'desc');
    }

    public function latestVoyage()
    {
        return $this->hasOne(Voyage::class, 'vessel_id', 'vessel_id')
            ->latest('voyage_id')    // Order by ID
            ->latest('updated_at');  // Fallback to timestamp
    }

    public function activeVoyage()
    {
        return $this->hasOne(Voyage::class, 'vessel_id', 'vessel_id')
            ->whereIn('status', ['active', 'docked', 'in_transmit'])
            ->latest('departure_date');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'related_vessel', 'vessel_id');
    }

    // ----------------------
    // Scopes
    // ----------------------

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    // ----------------------
    // Helper Methods
    // ----------------------

    /**
     * Get current status based on latest position
     */
    public function getStatusAttribute()
    {
        if (!$this->latestPosition) {
            return 'Unknown';
        }

        $speed = (float) $this->latestPosition->speed;

        if ($speed > 15) {
            return 'Active';
        } elseif ($speed > 0) {
            return 'In Transit';
        } else {
            return 'Docked';
        }
    }

    /**
     * Get formatted vessel information
     */
    public function getFormattedInfoAttribute()
    {
        return [
            'id' => $this->vessel_id,
            'name' => $this->name,
            'type' => $this->type,
            'imo' => $this->imo,
            'mmsi' => $this->mmsi,
            'flag' => $this->flag,
            'status' => $this->status,
            'latest_position' => $this->latestPosition ? [
                'latitude' => (float) $this->latestPosition->latitude,
                'longitude' => (float) $this->latestPosition->longitude,
                'speed' => (float) $this->latestPosition->speed,
                'recorded_at' => $this->latestPosition->recorded_at->format('Y-m-d H:i:s')
            ] : null,
            'active_voyage' => $this->activeVoyage ? [
                'voyage_id' => $this->activeVoyage->voyage_id,
                'departure_port' => $this->activeVoyage->departurePort->name ?? 'Unknown',
                'arrival_port' => $this->activeVoyage->arrivalPort->name ?? 'Unknown',
                'status' => $this->activeVoyage->status
            ] : null
        ];
    }

    /**
     * Create or update voyage for vessel movement
     */
    public function createVoyage($departurePortId, $arrivalPortId, $departureDate = null, $arrivalDate = null, $status = 'active')
    {
        // Complete any active voyages
        $this->voyages()
            ->whereIn('status', ['active', 'in_transmit'])
            ->update(['status' => 'docked']);

        // Create new voyage
        return $this->voyages()->create([
            'departure_port' => $departurePortId,
            'arrival_port' => $arrivalPortId,
            'departure_date' => $departureDate ?? now(),
            'arrival_date' => $arrivalDate,
            'status' => $status
        ]);
    }

    /**
     * Update vessel position
     */
    public function updatePosition($latitude, $longitude, $speed, $voyageId = null)
    {
        return $this->positions()->create([
            'voyage_id' => $voyageId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'speed' => $speed,
            'recorded_at' => now()
        ]);
    }
}
