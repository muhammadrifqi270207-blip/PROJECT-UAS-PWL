<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Simpan data lama
        $items = DB::table('order_items')->get();

        // Hapus tabel lama
        Schema::drop('order_items');

        // Buat ulang tabel dengan foreign key yang benar
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('tiket_id')->constrained('tikets')->onDelete('cascade');
            $table->integer('jumlah');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });

        // Restore data lama
        foreach ($items as $item) {
            DB::table('order_items')->insert((array) $item);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};