<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestRun;

class TestHistoryController extends Controller
{
public function index()
{
$testRuns = TestRun::with('questions')
->where('user_id', auth()->id())
->orderByDesc('started_at')
->get();

return view('history', compact('testRuns'));
}
}
