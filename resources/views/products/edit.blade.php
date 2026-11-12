
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk | Lp_Beauty</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff7f8;
            color: #49363d;
            padding: 30px 7%;
        }
        form {
            max-width: 600px;
            background: white;
            padding: 25px;
            border-radius: 14px;
        }
        label { display: block; margin-top: 15px; }
        input, select, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #e6cbd4;
            border-radius: 7px;
        }
        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #c96e8c;
            color: white;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
        }
        a { color: #a44c6b; }
        .error { color: #bd263e; }
    </style>
</head>
<body>
    <h1>Edit Produk ♡</h1>
    <p><a href="{{ route('products.index') }}">← Kembali</a></p>

    @if($errors->any())
        <ul class="error">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Produk</label>
        <input name="name" value="{{ old('name', $product->name) }}" required>

        <label>Brand</label>
        <input name="brand" value="{{ old('brand', $product->brand) }}">

        <label>Kategori</label>
        <select name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $product->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Harga (Rp)</label>
        <input type="number" name="price" min="0.01" step="0.01"
            value="{{ old('price', $product->price) }}" required>

        <label>Stok</label>
        <input type="number" name="stock" min="0"
            value="{{ old('stock', $product->stock) }}" required>

        <label>Deskripsi</label>
        <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
