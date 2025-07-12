<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostOverview extends Model
{
    protected $table = 'post_overview';

    public $timestamps = false;

    protected $fillable = [
        'totalEvent_posted',
        'totalProposal_event',
        'totalAccepted_event',
    ];
}
