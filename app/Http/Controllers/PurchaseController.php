<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest()
            ->get();

        return view('purchases.index', compact('purchases'));
    }
    
    public function show($id)
    {
        $purchase = Purchase::with([
            'supplier',
            'user',
            'items.product'
        ])->findOrFail($id);

        return view('purchases.show', compact('purchase'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|gt:0',
        ]);

        DB::transaction(function () use ($validated) {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'invoice_number' => 'PB-' . now()->format('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => 0,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $subtotal = round($quantity * $unitPrice, 2);
                $stockBefore = (int) $product->stock;
                $stockAfter = $stockBefore + $quantity;

                if ($stockAfter < 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Jumlah stok tidak valid.',
                    ]);
                }

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->stock = $stockAfter;
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'description' => 'Pembelian ' . $purchase->invoice_number,
                ]);

                $total += $subtotal;
            }

            $purchase->update(['total_amount' => $total]);
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Transaksi pembelian berhasil disimpan dan stok telah ditambahkan!');
    }
}
