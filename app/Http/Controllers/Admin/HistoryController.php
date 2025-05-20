<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HistoryController extends Controller
{
    /**
     * Zobrazenie zoznamu test runs so súčtami.
     */
    public function index()
    {
        // Dotaz na test_runs s počtom otázok a počtom správnych odpovedí
        $runs = TestRun::select('test_runs.*',
            DB::raw('COUNT(trq.run_q_id) AS total_questions'),
            DB::raw('SUM(trq.is_correct) AS correct_questions')
        )
            ->leftJoin('test_run_questions AS trq', 'test_runs.run_id', '=', 'trq.run_id')
            ->groupBy('test_runs.run_id')
            ->orderBy('test_runs.started_at', 'desc')
            ->paginate(20);

        return view('admin.history.index', compact('runs'));
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

            // Streamujeme všetky riadky
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
        $response->headers->set('Content-Disposition', 'attachment; filename="history.csv"');

        return $response;
    }

    /**
     * Vymazanie jedného záznamu.
     */
    public function destroy(TestRun $run)
    {
        $run->delete();

        return redirect()
            ->route('admin.history.index')
            ->with('success', 'Záznam bol vymazaný.');
    }
}


