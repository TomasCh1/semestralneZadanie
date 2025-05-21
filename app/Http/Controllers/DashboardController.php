<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * GET /dashboard/export-pdf   (name: dashboard.pdf)
     * – vygeneruje PDF so sekciou „Ako to funguje?“ v aktuálnom jazyku
     */
    public function exportPdf(Request $request)
    {
        /* 1) Nastavíme jazyk podľa session */
        $locale = session('locale', config('app.locale'));   // 'sk' alebo 'en'
        app()->setLocale($locale);

        /* 2) Získame pole prekladov krokov */
        $steps = __('dashboardL.steps');

        /* 3) Poskladáme HTML – používame e() na escapovanie */
        $html  = '<!DOCTYPE html><html lang="'.e($locale).'"><head>';
        $html .= '<meta charset="UTF-8"><style>
                    body{font-family:DejaVuSans,sans-serif;font-size:14px;}
                    h1{font-size:22px;margin-bottom:.4em;}
                    ol{margin-left:1.2em;}
                  </style></head><body>';

        $html .= '<h1>'.e(__('dashboardL.how_it_works')).'</h1><ol>';
        foreach ($steps as $step) {
            $html .= '<li>'.e($step).'</li>';
        }
        $html .= '</ol></body></html>';

        /* 4) HTML → PDF a download */
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('dpi', 120)
            ->setOption('defaultFont', 'DejaVuSans');

        return $pdf->download('test_steps_'.now()->format('Ymd_His').'.pdf');
    }
}
