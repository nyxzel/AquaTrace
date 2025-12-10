<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'event_id';
    public $timestamps = true;

    protected $fillable = [
        'event_type',
        'description',
        'event_date',
        'status',
        'logged_by'
    ];
}
