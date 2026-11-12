<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_return_id')
                ->constrained('sale_returns')
                ->cascadeOnDelete();

            $table->foreignId('sale_item_id')
                ->constrained('sale_items')
                ->restrictOnDelete();

            // Jumlah barang yang dikembalikan
            $table->unsignedInteger('quantity');

            // Kondisi barang setelah diterima kembali
            $table->enum('condition', [
                'resellable',
                'damaged',
            ]);

            // Harga produk pada saat transaksi penjualan
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);

            // Produk pengganti, jika prosesnya adalah penukaran
            $table->foreignId('replacement_product_id')
                ->nullable()
                ->constrained('products')
                ->restrictOnDelete();

            $table->unsignedInteger('replacement_quantity')
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_return_items');
    }
};
