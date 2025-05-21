<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\TestRun;
use App\Models\TestRunQuestion;

class TestController extends Controller
{
    /** Zobrazí aktuálnu otázku, alebo po skončení testu stránku s výsledkami. */
    public function show(Request $request, TestRun $run)
    {
        /* 1️⃣ – ak ešte nemáme otázky v session, vygeneruj 15 náhodných */
        $key    = "run:{$run->run_id}:questions";
        $idxKey = "run:{$run->run_id}:idx";

        if (!$request->session()->has($key) || empty($request->session()->get($key))) {

            $total = Question::count();
            if ($total === 0) {
                return back()->with('error', 'V databáze nie sú žiadne otázky.');
            }

            $ids = Question::inRandomOrder()
                ->limit(min(15, $total))
                ->pluck('question_id')
                ->toArray();

            $request->session()->put($key,  $ids);
            $request->session()->put($idxKey, 0);
        }

        $ids     = $request->session()->get($key);
        $current = $request->session()->get($idxKey, 0);

        /* ak sme na konci zoznamu, priprav štatistiky + detaily a zobraz výsledky */
        if ($current >= count($ids)) {

            $stats = DB::table('test_run_questions')
                ->where('run_id', $run->run_id)
                ->selectRaw('
                           SUM(is_correct)                 AS ok,
                           COUNT(*)                        AS total,
                           COALESCE(SUM(time_spent_sec),0) AS secs
                       ')
                ->first();

            // podrobný výpis otázok + odpovedí
            $details = TestRunQuestion::with(['question.choices'])
                ->where('run_id', $run->run_id)
                ->orderBy('shown_order')
                ->get();

            $tz = $run->timezone ?? 'UTC';
            $run->finished_at = now()->setTimezone($tz);
            $run->save();

            return view('tests.finished', [
                'run'     => $run,
                'stats'   => $stats,
                'details' => $details,
            ]);
        }

        /* 3️⃣ – načítaj aktuálnu otázku */
        $questionId = $ids[$current];
        $question   = Question::findOrFail($questionId);

        /* 4️⃣ – ak ešte nie je v test_run_questions, zapíš začiatok */
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

    /** Spracuje odpoveď na otázku a presmeruje na ďalšiu. */
    public function answer(Request $request, TestRun $run)
    {
        $questionId = (int) $request->input('question_id');
        $answer     = trim($request->input('answer'));

        $trq = TestRunQuestion::where('run_id', $run->run_id)
            ->where('question_id', $questionId)
            ->firstOrFail();

        /* vyhodnotenie správnosti */
        $correct = false;
        if ($question = Question::find($questionId)) {

            if ($question->type === Question::TYPE_MCQ) {
                $correct = DB::table('choices')
                        ->where('choice_id', $answer)
                        ->value('is_correct') == 1;
            } else {                                 // TEXT
                $correct = strcasecmp(
                        preg_replace('/\s+/', '', $answer),
                        preg_replace('/\s+/', '', $question->correct_answer)
                    ) === 0;
            }
        }

        /* výpočet času na otázke */
        $started = $trq->started_at ?? now();
        $spent   = now()->diffInSeconds($started);

        /* ulož výsledok */
        $trq->update([
            'answered_at'    => now(),
            'user_answer'    => $answer,
            'is_correct'     => $correct,
            'time_spent_sec' => $spent,
        ]);

        /* posuň index otázky v session */
        $idxKey = "run:{$run->run_id}:idx";
        $request->session()->increment($idxKey);

        return redirect()->route('tests.question', $run->run_id);
    }
}