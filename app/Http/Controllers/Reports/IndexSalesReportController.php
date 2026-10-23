<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\SalesReportRequest;
use App\Services\SalesReportService;
use Illuminate\View\View;

class IndexSalesReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SalesReportRequest $request, SalesReportService $report): View
    {
        [$start, $end] = $request->range();

        return view('reports.index', [
            'start'               => $start,
            'end'                 => $end,
            'summary'             => $report->summary($start, $end),
            'dailyTrend'          => $report->dailyTrend($start, $end),
            'paymentRatio'        => $report->paymentRatio($start, $end),
            'categoryComposition' => collect($report->categoryComposition($start, $end)),
            'topProducts'         => collect($report->topProducts($start, $end)),
            'dailyBreakdown'      => collect($report->dailyBreakdown($start, $end)), // <-- Dibungkus collect()
        ]);
    }
}
