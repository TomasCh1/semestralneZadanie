<?php

// app/Http/Controllers/TestRunController.php
namespace App\Http\Controllers;

use App\Models\TestRun;
use Illuminate\Http\Request;


class TestRunController extends Controller
{
    /**
     * Vytvorí nový test_run a presmeruje na riešenie testu.
     */
    public function store(Request $request)
    {
        $tz = $request->input('timezone', 'UTC'); // fallback

        // ľubovoľné ďalšie údaje (city, state, run_number) — nechávam z tvojho kódu
        $userId    = auth()->id();
        $runNumber = TestRun::where('user_id', $userId)->max('run_number') + 1;

        // voliteľná geolokácia (zadarmo cez ip-api)
        $city  = null;
        $state = null;
        try {
            $loc = @json_decode(file_get_contents('http://ip-api.com/json/'.$request->ip()));
            if ($loc && $loc->status === 'success') {
                $city  = $loc->city;
                $state = $loc->country;
            }
        } catch (\Throwable $e) {
            // necháme prázdne, nebudeme plakať
        }

        // —— 2. zapíš do DB ————————————————————————————
        $testRun = TestRun::create([
            'user_id'          => $userId,
            'anon_id'          => null,
            'started_at'       => now(),                 // UTC
            'started_at_local' => now()->setTimezone($tz),
            'timezone'         => $tz,
            'run_number'       => $runNumber,
            'city'       => $city,
            'state'      => $state,
        ]);

        // —— 3. kam ďalej? ——————————————————————————————
        // napr. /test/{run_id}
        return redirect()->route('tests.question', $testRun->run_id);
    }
}
