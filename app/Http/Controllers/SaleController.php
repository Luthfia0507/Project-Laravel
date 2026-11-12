<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user')
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'PJ-' . now()->format('YmdHis')
                    . '-' . Str::upper(Str::random(5)),
                'sale_date' => $validated['sale_date'],
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

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $stockBefore = $product->stock;
                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->stock = $stockBefore - $quantity;
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $product->stock,
                    'description' => 'Penjualan ' . $sale->invoice_number,
                ]);

                $total += $subtotal;
            }

            $sale->update(['total_amount' => $total]);
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Transaksi penjualan berhasil disimpan!');
    }

    public function show(Sale $sale)
    {
        $sale->load([
            'user',
            'items.product',
        ]);

        return view('sales.show', compact('sale'));
    }
}