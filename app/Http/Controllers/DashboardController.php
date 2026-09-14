<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RepairOrder;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Métricas Principales (KPIs)
        $totalSalesIncome = Sale::sum('total');
        $totalRepairsIncome = RepairOrder::where('status', 'Entregado')->sum('estimated_cost');
        $lowStockCount = Product::where('stock', '<=', 5)->count();
        $activeRepairsCount = RepairOrder::whereIn('status', ['Recibido', 'En Revision', 'En Reparacion'])->count();

        // 2. Últimos movimientos
        $recentSales = Sale::with('client')->latest()->take(5)->get();
        $recentRepairs = RepairOrder::with('client')->latest()->take(5)->get();

        // 3. Ventas de los últimos 7 días
        $salesLast7Days = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get()
        ->pluck('total', 'date')
        ->toArray();

        $chartDates = [];
        $chartSalesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDates[] = Carbon::parse($date)->format('d/m');
            $chartSalesData[] = $salesLast7Days[$date] ?? 0;
        }

        // 4. Estados del Taller
        $repairsByStatus = RepairOrder::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalSalesIncome',
            'totalRepairsIncome',
            'lowStockCount',
            'activeRepairsCount',
            'recentSales',
            'recentRepairs',
            'chartDates',
            'chartSalesData',
            'repairsByStatus'
        ));
    }
}
