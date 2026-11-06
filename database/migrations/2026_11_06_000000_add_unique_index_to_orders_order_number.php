<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Duplikat lama tidak dihapus otomatis karena bisa saja dua-duanya sudah punya pembayaran.
        // Bersihkan manual dulu (lihat docs/06-modul/pesanan.md), lalu jalankan migrate lagi.
        $duplicates = DB::table('orders')
            ->select('order_number')
            ->groupBy('order_number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('order_number');

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException('orders.order_number masih ada yang dobel: ' . $duplicates->implode(', '));
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->unique('order_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['order_number']);
        });
    }
};
