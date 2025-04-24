<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Fechas para comparativas
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::today()->subWeek();
        
        // 1. Órdenes hoy
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $ordersYesterday = Order::whereDate('created_at', $yesterday)->count();
        $ordersPercentageChange = $ordersYesterday > 0 
            ? round((($ordersToday - $ordersYesterday) / $ordersYesterday) * 100, 2)
            : 100;

        // 2. Clientes nuevos
        $newCustomersToday = User::whereDate('created_at', $today)->count();
        $newCustomersLastWeek = User::whereBetween('created_at', [$lastWeek, $yesterday])->count();
        $avgNewCustomersLastWeek = $newCustomersLastWeek > 0 
            ? round($newCustomersLastWeek / 7, 2)
            : 0;
        $customersPercentageChange = $avgNewCustomersLastWeek > 0 
            ? round((($newCustomersToday - $avgNewCustomersLastWeek) / $avgNewCustomersLastWeek) * 100, 2)
            : 100;

        // 3. Ingresos hoy
        $revenueToday = Order::whereDate('created_at', $today)
            ->where('status', 4) // Asumiendo que 4 es "Completed"
            ->sum('total');
        $revenueYesterday = Order::whereDate('created_at', $yesterday)
            ->where('status', 4)
            ->sum('total');
        $revenuePercentageChange = $revenueYesterday > 0 
            ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 2)
            : 100;

        // 4. Órdenes pendientes
        $pendingOrders = Order::where('status', 2)->count(); // Asumiendo que 1 es "Pending"
        $pendingOrdersLastWeek = Order::where('status', 1)
            ->whereBetween('created_at', [$lastWeek, $yesterday])
            ->count();
        $pendingPercentageChange = $pendingOrdersLastWeek > 0 
            ? round((($pendingOrders - $pendingOrdersLastWeek) / $pendingOrdersLastWeek) * 100, 2)
            : 0;

        // 5. Productos más vendidos (para otra sección)
        $topProducts = DB::table('order_items')
            ->select('variant_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('variant_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // 6. Últimas órdenes
        $recentOrders = Order::with(['user', 'items.variant.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', [
            'ordersToday' => $ordersToday,
            'ordersPercentageChange' => $ordersPercentageChange,
            'newCustomersToday' => $newCustomersToday,
            'customersPercentageChange' => $customersPercentageChange,
            'revenueToday' => $revenueToday,
            'revenuePercentageChange' => $revenuePercentageChange,
            'pendingOrders' => $pendingOrders,
            'pendingPercentageChange' => $pendingPercentageChange,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ]);
    }
}