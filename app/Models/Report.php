<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $primaryKey = 'report_id';

    // Laravel timestamps (created_at, updated_at) are disabled
    // We use custom fields: date_created and updated_on
    public $timestamps = false;

    protected $fillable = [
        'report_type',
        'severity',
        'description',
        'additional_notes',
        'incident_date',
        'incident_time',
        'admin_id',
        'related_vessel',
        'date_created',
        'updated_on',
        'port_id',
        'status',
        'created_by',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'date_created' => 'datetime',
        'updated_on' => 'datetime',
        'reported_at' => 'datetime',
        'incident_date' => 'date',
    ];

    protected $attributes = [
        'is_archived' => 0,
        'severity' => 'LOW',
        'status' => 'Pending',
    ];

    // Relationship to Vessel (using related_vessel field)
    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'related_vessel', 'vessel_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship to Port
    public function port()
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes for filtering
    public function scopeActive($query)
    {
        return $query->where('is_archived', 0);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', 1);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Get owner through vessel relationship
    public function getOwnerAttribute()
    {
        return $this->vessel?->owner;
    }

    // Get full port location
    public function getFullPortLocationAttribute()
    {
        if (!$this->port) {
            return 'N/A';
        }
        return $this->port->name;
    }
}
