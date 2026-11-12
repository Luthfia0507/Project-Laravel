<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;

// Login untuk pengguna yang belum masuk
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

// Semua halaman aplikasi wajib login
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Produk
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('role:admin')
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('role:admin')
        ->name('products.store');

    // Membuat SKU otomatis berdasarkan kategori
    Route::get('/products/next-sku', [ProductController::class, 'nextSku'])
        ->middleware('role:admin')
        ->name('products.next-sku');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('role:admin')
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('role:admin')
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('products.destroy');

    // Dashboard khusus Admin
    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard');

    // Kategori khusus Admin
    Route::middleware('role:admin')->group(function () {

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        // Edit kategori
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        // Simpan perubahan kategori
        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');
    });

    // Manajemen stok: Admin dan Staff
    Route::get('/stock-movements', [StockMovementController::class, 'index'])
        ->name('stock-movements.index');

    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])
        ->name('stock-movements.create');

    Route::post('/stock-movements', [StockMovementController::class, 'store'])
        ->name('stock-movements.store');

    // Manajemen Supplier khusus Admin
    Route::middleware('role:admin')->group(function () {

        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store');

        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
            ->name('suppliers.edit');

        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])
            ->name('suppliers.update');

        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');
    });

    // Transaksi pembelian khusus Admin
    Route::middleware('role:admin')->group(function () {

        Route::get('/purchases', [PurchaseController::class, 'index'])
            ->name('purchases.index');

        Route::get('/purchases/create', [PurchaseController::class, 'create'])
            ->name('purchases.create');

        Route::get('/purchases/{id}', [PurchaseController::class, 'show'])
            ->whereNumber('id')
            ->name('purchases.show');

        Route::post('/purchases', [PurchaseController::class, 'store'])
            ->name('purchases.store');
    });

    // Transaksi penjualan dan pengembalian
    Route::middleware('role:admin,staff')->group(function () {

        Route::get('/sales', [SaleController::class, 'index'])
            ->name('sales.index');

        Route::get('/sales/create', [SaleController::class, 'create'])
            ->name('sales.create');

        Route::get('/sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');

        Route::post('/sales', [SaleController::class, 'store'])
            ->name('sales.store');

        Route::get('/sales/{sale}/returns/create', [SaleReturnController::class, 'create'])
            ->name('sales.returns.create');

        Route::post('/sales/{sale}/returns', [SaleReturnController::class, 'store'])
            ->name('sales.returns.store');
    });
});