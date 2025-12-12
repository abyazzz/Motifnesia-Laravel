<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman Laporan Penjualan.
     */
    public function index(Request $request)
    {
        // Get filter period
        $period = $request->get('period', '30'); // default 30 days
        
        // Calculate date range
        $endDate = Carbon::now();
        $startDate = $this->getStartDate($period, $endDate);
        
        // Get metrics
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_bayar');
            
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $totalProductsSold = OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('qty');
            
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Get orders for table
        $orders = Order::with(['user', 'orderItems.produk', 'metodePembayaran', 'metodePengiriman'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get top products
        $topProducts = OrderItem::select('produk_id', DB::raw('SUM(qty) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('produk_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with('produk')
            ->get();
        
        // Get daily sales for chart (last 7 or 30 days)
        $chartDays = min((int)$period, 30);
        $dailySales = Order::selectRaw('DATE(created_at) as date, SUM(total_bayar) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Fill missing dates with 0
        $chartData = $this->fillMissingDates($dailySales, $startDate, $endDate);
        
        return view('admin.pages.salesReport', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalProductsSold' => $totalProductsSold,
            'averageOrderValue' => $averageOrderValue,
            'orders' => $orders,
            'topProducts' => $topProducts,
            'chartData' => $chartData,
            'currentPeriod' => $period,
            'activePage' => 'sales-report'
        ]);
    }
    
    /**
     * Get start date based on period
     */
    private function getStartDate($period, $endDate)
    {
        switch($period) {
            case 'today':
                return Carbon::today();
            case '7':
                return Carbon::now()->subDays(7);
            case '30':
                return Carbon::now()->subDays(30);
            case 'month':
                return Carbon::now()->startOfMonth();
            default:
                return Carbon::now()->subDays(30);
        }
    }
    
    /**
     * Fill missing dates with 0 sales
     */
    private function fillMissingDates($sales, $startDate, $endDate)
    {
        $result = [];
        $salesByDate = $sales->keyBy('date');
        
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $result[] = [
                'date' => $currentDate->format('d M'),
                'total' => $salesByDate->has($dateStr) ? $salesByDate[$dateStr]->total : 0
            ];
            $currentDate->addDay();
        }
        
        return $result;
    }
    
    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        $period = $request->get('period', '30');
        $endDate = Carbon::now();
        $startDate = $this->getStartDate($period, $endDate);
        
        $orders = Order::with(['user', 'orderItems.produk'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Create CSV
        $filename = 'sales_report_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Order Number', 'Date', 'Customer', 'Products', 'Total', 'Payment Method']);
            
            // Data
            foreach ($orders as $order) {
                $products = $order->orderItems->pluck('nama_produk')->implode(', ');
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d M Y H:i'),
                    $order->user->full_name ?? $order->user->name,
                    $products,
                    'Rp ' . number_format($order->total_bayar, 0, ',', '.'),
                    $order->metodePembayaran->nama ?? '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}