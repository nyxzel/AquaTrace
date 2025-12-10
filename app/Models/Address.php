<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'address_id';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'street_no',
        'post_code',
        'city',
        'state',
        'country',
        'contact',
        'email',
    ];


    public function owner()
    {
        return $this->hasMany(Owner::class, 'address_id', 'address_id');
    }

    // Relationship to ports
    public function ports()
    {
        return $this->hasMany(Port::class, 'address_id', 'address_id');
    }

    // Helper to get full address string
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->street_no,
            $this->city,
            $this->state,
            $this->country,
            $this->post_code
        ]);

        return implode(', ', $parts);
    }
}
