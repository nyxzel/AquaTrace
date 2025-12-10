<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Port extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ports';
    protected $primaryKey = 'port_id';
    protected $fillable = [
        'name',
        'address_id',
        'position_id',
    ];
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationship to Address
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }


    // Relationship to reports
    public function reports()
    {
        return $this->hasMany(Report::class, 'port_id', 'port_id');
    }

    // Relationship to positions
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    // Helper to get full location string
    public function getFullLocationAttribute()
    {
        if (!$this->address) {
            return $this->name;
        }

        $parts = array_filter([
            $this->address->street_no ?? null,
            $this->address->city ?? null,
            $this->address->state ?? null,
            $this->address->country ?? null
        ]);

        return $this->name . (!empty($parts) ? ' (' . implode(', ', $parts) . ')' : '');
    }
}
