<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_archived'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_archived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the owner profile for this user
     */
    public function owner()
    {
        return $this->hasOne(Owner::class, 'user_id', 'id');
    }

    /**
     * Get the admin profile for this user
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'id');
    }

    /**
     * Get all vessel registration requests by this user
     */
    public function vesselRequests()
    {
        return $this->hasMany(VesselRegistrationRequest::class, 'user_id', 'id');
    }

    /**
     * Get pending vessel requests
     */
    public function pendingVesselRequests()
    {
        return $this->vesselRequests()->where('status', 'pending');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is owner
     */
    public function isOwner()
    {
        return $this->role === 'owner' || $this->role === 'user';
    }

    /**
     * Scope for active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope for owners only
     */
    public function scopeOwners($query)
    {
        return $query->whereIn('role', ['owner', 'user']);
    }

    /**
     * Scope for admins only
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}
