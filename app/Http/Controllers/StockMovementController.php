<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();

        $movements = StockMovement::with('product')
            ->latest()
            ->get();

        return view('stock-movements.index', compact('products', 'movements'));
    }

    public function create()
    {
        return redirect()->route('stock-movements.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $before = $product->stock;
            $quantity = (int) $validated['quantity'];

            if ($validated['type'] === 'out' && $quantity > $before) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok keluar melebihi stok tersedia. Stok saat ini: '
                        . $before . ' unit.',
                ]);
            }

            $after = $validated['type'] === 'in'
                ? $before + $quantity
                : $before - $quantity;

            $product->stock = $after;
            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'stock_before' => $before,
                'stock_after' => $after,
                'description' => $validated['description'] ?? null,
            ]);
        });

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Transaksi stok berhasil disimpan!');
    }
}