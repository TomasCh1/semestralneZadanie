<?php

// app/Http/Controllers/GuestTestController.php
namespace App\Http\Controllers;

use App\Models\Question;

class GuestTestController extends Controller
{
    /** Zobrazí 15 náhodných otázok na jednej stránke. */
    public function start()
    {
        $questions = Question::with('choices')
            ->inRandomOrder()
            ->limit(15)
            ->get();

        // ✨ doplníme premenne, ktoré šablóna očakáva
        $current = 0;               // začíname prvou otázkou
        $total   = $questions->count();   // zvyčajne 15

        return view('guest.tests.start', compact('questions', 'current', 'total'));
    }

    /** Stránka s výsledkom – číta sa iba z localStorage. */
    public function finished()
    {
        return view('guest.tests.finished');
    }
}
