<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';
    protected $primaryKey = 'position_id';

    public $timestamps = false; // you set recorded_at manually

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

    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'vessel_id', 'vessel_id');
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class, 'voyage_id', 'voyage_id');
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('recorded_at', '>=', now()->subHours($hours));
    }

    public function scopeByVessel($query, $vesselId)
    {
        return $query->where('vessel_id', $vesselId);
    }

    public function getFormattedCoordinatesAttribute()
    {
        return [
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude
        ];
    }

    public function isMoving()
    {
        return $this->speed > 0;
    }

    public static function recordPosition($vesselId, $latitude, $longitude, $speed = 0, $voyageId = null)
    {
        return self::create([
            'vessel_id' => $vesselId,
            'voyage_id' => $voyageId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'speed' => $speed,
            'recorded_at' => now()
        ]);
    }
}
