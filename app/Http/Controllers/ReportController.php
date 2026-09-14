<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * US-014: Generación y filtrado de reportes financieros consolidados.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // 1. Consultar ventas en el rango
        $sales = Sale::with(['client', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        // 2. Consultar servicios técnicos entregados/cobrados en el rango
        $repairs = RepairOrder::with(['client', 'technician'])
            ->where('status', 'Entregado')
            ->whereBetween('updated_at', [$start, $end])
            ->latest()
            ->get();

        // 3. Totales consolidados
        $totalSalesIncome = $sales->sum('total');
        $totalRepairsIncome = $repairs->sum('estimated_cost');
        $grandTotal = $totalSalesIncome + $totalRepairsIncome;

        return view('admin.reports.index', compact(
            'sales',
            'repairs',
            'startDate',
            'endDate',
            'totalSalesIncome',
            'totalRepairsIncome',
            'grandTotal'
        ));
    }
}
