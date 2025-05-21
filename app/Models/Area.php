<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    protected $table = 'areas';
    protected $primaryKey = 'area_id';
    public $timestamps = false;
    protected $fillable = ['name'];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'question_areas',    // pivot table
            'area_id',           // foreign key on pivot for this model
            'question_id'        // foreign key on pivot for the other model
        );
    }
}
