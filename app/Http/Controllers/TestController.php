<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\TestRun;
use App\Models\TestRunQuestion;

class TestController extends Controller
{
    /** Zobrazí aktuálnu otázku, prípadne výsledkovú obrazovku. */
    public function show(Request $request, TestRun $run)
    {
        // 1️⃣ – vygeneruj 15 náhodných otázok, ak ešte nie sú v session
        $key    = "run:{$run->run_id}:questions";
        $idxKey = "run:{$run->run_id}:idx";

        if (!$request->session()->has($key)) {
            $ids = Question::inRandomOrder()
                ->limit(15)
                ->pluck('question_id')
                ->toArray();

            $request->session()->put($key,  $ids);
            $request->session()->put($idxKey, 0);
        }

        $ids     = $request->session()->get($key);
        $current = $request->session()->get($idxKey, 0);

        // 2️⃣ – ak sme na konci zoznamu, spočítaj štatistiky a zobraz výsledky
        if ($current >= count($ids)) {

            $stats = DB::table('test_run_questions')
                ->where('run_id', $run->run_id)
                ->selectRaw('
                           SUM(is_correct)           AS ok,
                           COUNT(*)                  AS total,
                           COALESCE(SUM(time_spent_sec),0) AS secs
                       ')
                ->first();

            return view('tests.finished', [
                'run'   => $run,
                'stats' => $stats,
            ]);
        }

        // 3️⃣ – načítaj práve zobrazovanú otázku
        $questionId = $ids[$current];
        $question   = Question::findOrFail($questionId);

        // 4️⃣ – ešte nie je v test_run_questions? → vložiť s started_at
        TestRunQuestion::firstOrCreate(
            ['run_id' => $run->run_id, 'question_id' => $questionId],
            [
                'shown_order' => $current + 1,
                'started_at'  => now(),
            ]
        );

        return view('tests.question', [
            'run'      => $run,
            'question' => $question,
            'current'  => $current,
        ]);
    }

    /** Spracuje odpoveď a presmeruje na ďalšiu otázku. */
    public function answer(Request $request, TestRun $run)
    {
        $questionId = (int) $request->input('question_id');
        $answer     = trim($request->input('answer'));

        $trq = TestRunQuestion::where('run_id', $run->run_id)
            ->where('question_id', $questionId)
            ->firstOrFail();

        // ▶︎ vyhodnotenie správnosti
        $correct = false;
        if ($question = Question::find($questionId)) {

            if ($question->type === 'MCQ') {
                $correct = DB::table('choices')
                        ->where('choice_id', $answer)
                        ->value('is_correct') == 1;
            } else {                    // TEXT
                $correct = strcasecmp(
                        preg_replace('/\s+/', '', $answer),
                        preg_replace('/\s+/', '', $question->correct_answer)
                    ) === 0;
            }
        }

        // ▶︎ výpočet času stráveného na otázke
        $started = $trq->started_at ?? now();
        $spent   = now()->diffInSeconds($started);

        // ▶︎ update riadku
        $trq->update([
            'answered_at'    => now(),
            'user_answer'    => $answer,
            'is_correct'     => $correct,
            'time_spent_sec' => $spent,
        ]);

        // ▶︎ posuň index v session
        $idxKey = "run:{$run->run_id}:idx";
        $request->session()->increment($idxKey);

        return redirect()->route('tests.question', $run->run_id);
    }
}
