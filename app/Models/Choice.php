<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $table = 'choices';         // nemusíš, ale môžeš
    protected $primaryKey = 'choice_id';  // podľa DB schémy
    public $timestamps = false;           // nemáš created_at/updated_at

    protected $fillable = [
        'question_id',
        'text',
        'is_correct',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
