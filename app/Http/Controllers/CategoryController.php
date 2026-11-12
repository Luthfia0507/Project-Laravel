<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name'),
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori tersebut sudah tersedia.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        Category::create($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')
                    ->ignore($category->id),
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori tersebut sudah digunakan.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Nama kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan oleh produk. Ubah nama kategori atau pindahkan produk ke kategori lain terlebih dahulu.'
                );
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}