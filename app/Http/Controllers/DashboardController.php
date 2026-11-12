<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        $totalPurchases = Purchase::count();
        $totalSales = Sale::count();

        // Nilai transaksi secara keseluruhan
        $purchaseAmount = Purchase::sum('total_amount');
        $salesAmount = Sale::sum('total_amount');

        // Produk dengan stok menipis
        $lowStockProducts = Product::with('category')
            ->where('stock', '>', 0)
            ->where('stock', '<=', 3)
            ->orderBy('stock')
            ->take(3)
            ->get();

        // Produk yang stoknya habis
        $outOfStockProducts = Product::with('category')
            ->where('stock', '<=', 0)
            ->orderBy('name')
            ->take(3)
            ->get();

        // Produk terbaru
        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Pembelian terbaru
        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Penjualan terbaru
        $recentSales = Sale::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Pergerakan stok terbaru
        $recentStockMovements = StockMovement::with('product')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalCategories',
            'totalSuppliers',
            'totalPurchases',
            'totalSales',
            'purchaseAmount',
            'salesAmount',
            'lowStockProducts',
            'outOfStockProducts',
            'recentProducts',
            'recentPurchases',
            'recentSales',
            'recentStockMovements'
        ));
    }
}