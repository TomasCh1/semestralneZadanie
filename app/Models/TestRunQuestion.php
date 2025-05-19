<?php

// app/Models/TestRunQuestion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestRunQuestion extends Model
{
    public    $timestamps = false;
    protected $table      = 'test_run_questions';
    protected $primaryKey = 'run_q_id';

    protected $fillable = [
        'run_id', 'question_id', 'shown_order',
        'started_at', 'answered_at',
        'is_correct', 'user_answer', 'time_spent_sec',
    ];
}

