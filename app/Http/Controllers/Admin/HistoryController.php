<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestRun;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistoryController extends Controller
{
    /**
     * Zobrazenie zoznamu test runs so súčtami.
     */
    public function index()
    {
        $users = DB::table('users')
            ->select('id as user_id', 'name as user_name', 'email as user_email')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.history.index', compact('users'));
    }

    /**
     * Zobrazenie detailu jedného test-runu (otázky + stav).
     */
    public function show($userId)
    {
        $runs = TestRun::select(
            'test_runs.*',
            DB::raw('COUNT(trq.run_q_id) AS total_questions'),
            DB::raw('SUM(trq.is_correct) AS correct_questions')
        )
            ->leftJoin('test_run_questions AS trq', 'test_runs.run_id', '=', 'trq.run_id')
            ->where('test_runs.user_id', $userId) // filtrovanie podľa konkrétneho používateľa
            ->groupBy('test_runs.run_id')
            ->orderBy('test_runs.started_at', 'desc')
            ->paginate(20);

        return view('admin.history.show', compact('runs'));
    }
    public function details($userId, $runId)
    {
        // Overíme, či daný test patrí zadanému používateľovi
        $run = TestRun::where('run_id', $runId)
            ->where('user_id', $userId)
            ->firstOrFail();
        $questions = DB::table('test_run_questions as trq')
            ->join('questions as q', 'trq.question_id', '=', 'q.question_id')
            ->where('trq.run_id', $run->run_id)
            ->select(
                'q.question_id as id',
                'q.question_text as text',
                'trq.is_correct'
            )
            ->get();

        return view('admin.history.details', compact('run', 'questions'));
    }

    /**
     * Export zoznamu do CSV.
     */
    public function export()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // Hlavička CSV
            fputcsv($handle, [
                'Run ID',
                'User ID',
                'Anon ID',
                'Started At',
                'Finished At',
                'City',
                'State',
                'Total Questions',
                'Correct Questions',
            ]);

            DB::table('test_runs')
                ->leftJoin('test_run_questions AS trq', 'test_runs.run_id', '=', 'trq.run_id')
                ->select(
                    'test_runs.run_id',
                    'test_runs.user_id',
                    'test_runs.anon_id',
                    'test_runs.started_at',
                    'test_runs.finished_at',
                    'test_runs.city',
                    'test_runs.state',
                    DB::raw('COUNT(trq.run_q_id) AS total_questions'),
                    DB::raw('SUM(trq.is_correct) AS correct_questions')
                )
                ->groupBy(
                    'test_runs.run_id',
                    'test_runs.user_id',
                    'test_runs.anon_id',
                    'test_runs.started_at',
                    'test_runs.finished_at',
                    'test_runs.city',
                    'test_runs.state'
                )
                ->orderBy('test_runs.started_at', 'desc')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->run_id,
                            $row->user_id,
                            $row->anon_id,
                            $row->started_at,
                            $row->finished_at,
                            $row->city,
                            $row->state,
                            $row->total_questions,
                            $row->correct_questions,
                        ]);
                    }
                });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="history.csv"'
        );

        return $response;
    }

    /**
     * Vymazanie jedného záznamu vrátane jeho otázok.
     */
    public function destroy(TestRun $run)
    {
        // Najprv vymaž otázky patriace k tomuto run_id
        DB::table('test_run_questions')
            ->where('run_id', $run->run_id)
            ->delete();

        // Potom odstráň samotný test run
        $run->delete();

        return redirect()
            ->route('admin.history.index')
            ->with('success', 'Záznam bol vymazaný.');
    }
}
