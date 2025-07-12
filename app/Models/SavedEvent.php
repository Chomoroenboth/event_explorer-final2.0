<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedEvent extends Model
{
     protected $table = 'saved_events';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event_id',
    ];
}
