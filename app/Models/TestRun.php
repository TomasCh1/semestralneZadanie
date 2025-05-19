<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestRun extends Model
{
    protected $table = 'test_runs';
    protected $primaryKey = 'run_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'anon_id',
        'started_at',
        'started_at_local',
        'timezone',
        'run_number',
        'city',
        'state',
    ];

}

