<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'owners';
    protected $primaryKey = 'owner_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'address_id',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function vessels()
    {
        return $this->hasMany(Vessel::class, 'owner_id', 'owner_id');
    }
}
