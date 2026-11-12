<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleReturnController extends Controller
{
    /**
     * Menampilkan formulir retur untuk invoice tertentu.
     */
    public function create(Sale $sale)
    {
        $sale->load([
            'user',
            'items.product',
        ]);

        $products = Product::orderBy('name')->get();

        $returnedQuantities = SaleReturnItem::whereIn(
            'sale_item_id',
            $sale->items->pluck('id')
        )
            ->selectRaw('sale_item_id, SUM(quantity) as total_returned')
            ->groupBy('sale_item_id')
            ->pluck('total_returned', 'sale_item_id');

        return view('sales.returns.create', compact(
            'sale',
            'products',
            'returnedQuantities'
        ));
    }

    /**
     * Menyimpan retur dan memperbarui stok.
     */
    public function store(Request $request, Sale $sale)
    {
        abort_unless(auth()->check(), 401);
        $request->merge([
            'items' => collect($request->input('items', []))
                ->filter(fn ($item) => isset($item['selected']))
                ->map(function ($item) {
                    unset($item['selected']);
                    return $item;
                })
                ->values()
                ->all(),
        ]);
        $validated = $request->validate([
            'return_date' => ['required', 'date'],

            'resolution' => [
                'required',
                'in:return_only,exchange,refund',
            ],

            'reason' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.sale_item_id' => [
                'required',
                'integer',
                'distinct',
                'exists:sale_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.condition' => [
                'required',
                'in:resellable,damaged',
            ],

            'items.*.replacement_product_id' => [
                'nullable',
                'required_if:resolution,exchange',
                'integer',
                'exists:products,id',
            ],

            'items.*.replacement_quantity' => [
                'nullable',
                'required_if:resolution,exchange',
                'integer',
                'min:1',
            ],
        ]);

        return DB::transaction(function () use ($validated, $sale) {
            $returnNumber = 'RT-'
                . now()->format('YmdHis')
                . '-'
                . Str::upper(Str::random(4));

            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'user_id' => auth()->id(),
                'return_number' => $returnNumber,
                'return_date' => $validated['return_date'],
                'resolution' => $validated['resolution'],
                'reason' => $validated['reason'] ?? null,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $saleItem = SaleItem::where('sale_id', $sale->id)
                    ->where('id', $item['sale_item_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$saleItem) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Produk yang dipilih tidak ditemukan pada invoice ini.',
                    ]);
                }

                $previouslyReturned = SaleReturnItem::where(
                    'sale_item_id',
                    $saleItem->id
                )->sum('quantity');

                $remainingQuantity =
                    $saleItem->quantity - $previouslyReturned;

                $quantity = (int) $item['quantity'];

                if ($quantity > $remainingQuantity) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Jumlah retur melebihi sisa barang yang bisa diretur.',
                    ]);
                }

                $unitPrice = (float) $saleItem->unit_price;
                $subtotal = round($unitPrice * $quantity, 2);

                $replacementProductId =
                    $validated['resolution'] === 'exchange'
                        ? (int) $item['replacement_product_id']
                        : null;

                $replacementQuantity =
                    $validated['resolution'] === 'exchange'
                        ? (int) $item['replacement_quantity']
                        : 0;

                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->id,
                    'sale_item_id' => $saleItem->id,
                    'quantity' => $quantity,
                    'condition' => $item['condition'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'replacement_product_id' => $replacementProductId,
                    'replacement_quantity' => $replacementQuantity,
                ]);

                $totalAmount += $subtotal;

                // Tambahkan stok hanya jika barang masih layak dijual.
                if ($item['condition'] === 'resellable') {
                    $returnedProduct = Product::whereKey(
                        $saleItem->product_id
                    )->lockForUpdate()->firstOrFail();

                    $stockBefore = (int) $returnedProduct->stock;

                    $returnedProduct->stock =
                        $stockBefore + $quantity;

                    $returnedProduct->save();

                    StockMovement::create([
                        'product_id' => $returnedProduct->id,
                        'type' => 'in',
                        'quantity' => $quantity,
                        'stock_before' => $stockBefore,
                        'stock_after' => $returnedProduct->stock,
                        'description' =>
                            'Retur ' . $returnNumber
                            . ' dari invoice ' . $sale->invoice_number,
                    ]);
                }

                // Jika penukaran, kurangi stok produk pengganti.
                if ($validated['resolution'] === 'exchange') {
                    $replacementProduct = Product::whereKey(
                        $replacementProductId
                    )->lockForUpdate()->firstOrFail();

                    $replacementStockBefore =
                        (int) $replacementProduct->stock;

                    if (
                        $replacementStockBefore < $replacementQuantity
                    ) {
                        throw ValidationException::withMessages([
                            'items' =>
                                'Stok produk pengganti '
                                . $replacementProduct->name
                                . ' tidak mencukupi.',
                        ]);
                    }

                    $replacementProduct->stock =
                        $replacementStockBefore - $replacementQuantity;

                    $replacementProduct->save();

                    StockMovement::create([
                        'product_id' => $replacementProduct->id,
                        'type' => 'out',
                        'quantity' => $replacementQuantity,
                        'stock_before' => $replacementStockBefore,
                        'stock_after' => $replacementProduct->stock,
                        'description' =>
                            'Barang pengganti retur ' . $returnNumber
                            . ' untuk invoice ' . $sale->invoice_number,
                    ]);
                }
            }

            $saleReturn->update([
                'total_amount' => $totalAmount,
            ]);

            return redirect()
                ->route('sales.show', $sale->id)
                ->with(
                    'success',
                    'Retur berhasil disimpan. Nomor retur: '
                    . $returnNumber
                );
        });
    }
}
