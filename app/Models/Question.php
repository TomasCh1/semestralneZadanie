<?php

namespace App\Models;

use App\Models\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Question extends Model
{
    /* ────────  ZÁKLADNÉ NASTAVENIA  ──────── */

    protected $table = 'questions';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $fillable = [
        'type', 'question_text', 'correct_answer', 'created_at',
    ];

    /* ────────  KONŠTANTY  (TU bol problém)  ──────── */

    public const TYPE_MCQ  = 'MCQ';
    public const TYPE_TEXT = 'TEXT';

    /* ────────  VZŤAHY  ──────── */

    public function choices()
    {
        return $this->hasMany(Choice::class, 'question_id', 'question_id');
    }


    public function runs(): HasMany
    {
        return $this->hasMany(TestRunQuestion::class, 'question_id', 'question_id');
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(
            Area::class,
            'question_areas',
            'question_id',
            'area_id'
        );
    }

    /* ────────  POMOCNÁ METÓDA  ──────── */

    /** Vyhodnotí správnosť odpovede (MCQ aj TEXT). */
    public function evaluate(string $userAnswer): bool
    {
if ($this->type === self::TYPE_MCQ) {
            // pri MCQ posielame choice_id
            return $this->choices()
                ->where('choice_id', $userAnswer)
                ->where('is_correct', 1)
                ->exists();
        }

        // TEXT – ignoruj veľkosť písmen a medzery
        return strcasecmp(
                preg_replace('/\s+/', '', $userAnswer),
                preg_replace('/\s+/', '', $this->correct_answer)
            ) === 0;
    }

}
