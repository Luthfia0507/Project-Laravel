<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pencarian berdasarkan nama, merek, atau SKU
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('brand', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        $products = $query->latest()->get();

        $search = $request->search;
        $selectedCategory = $request->category;

        return view('products.index', compact(
            'products',
            'search',
            'selectedCategory'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Menghasilkan SKU berikutnya berdasarkan kategori.
     */
    public function nextSku(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        return response()->json([
            'sku' => $this->generateNextSku($category),
        ]);
    }

    /**
     * Menyimpan produk dengan SKU yang ditentukan oleh server.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        // SKU ditentukan oleh Laravel, bukan dari input browser.
        $validated['sku'] = $this->generateNextSku($category);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan dengan SKU ' . $validated['sku'] . '!');
    }

    /**
     * Membuat SKU berikutnya sesuai kategori.
     */
    private function generateNextSku(Category $category): string
    {
        $prefix = match (strtolower(trim($category->name))) {
            'make-up' => 'MSK',
            'skincare' => 'SKN',
            'personal care' => 'PRD',
            default => null,
        };

        if ($prefix === null) {
            throw ValidationException::withMessages([
                'category_id' => 'Kategori ini belum memiliki format SKU. Silakan hubungi administrator.',
            ]);
        }

        // Ambil SKU yang memiliki prefix sesuai kategori.
        $existingSkus = Product::where('sku', 'like', $prefix . '-%')
            ->pluck('sku');

        $highestNumber = 0;

        foreach ($existingSkus as $sku) {
            if (preg_match('/^' . $prefix . '-(\d+)$/', $sku, $matches)) {
                $number = (int) $matches[1];

                if ($number > $highestNumber) {
                    $highestNumber = $number;
                }
            }
        }

        $nextNumber = $highestNumber + 1;

        return $prefix . '-' . str_pad(
            (string) $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()
                    ->route('products.index')
                    ->with(
                        'error',
                        'Produk tidak dapat dihapus karena masih digunakan dalam riwayat transaksi. Kamu tetap bisa mengedit informasi produknya.'
                    );
            }

            Log::error('Gagal menghapus produk.', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('products.index')
                ->with(
                    'error',
                    'Produk gagal dihapus karena terjadi kesalahan database.'
                );
        }
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }
}