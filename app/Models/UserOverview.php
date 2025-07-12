<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOverview extends Model
{
        protected $table = 'user_overview';

    public $timestamps = false;

    protected $fillable = [
        'active_users',
        'total_users',
        'totalRegister_users',
        'new_users',
    ];
}
