<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pengadaan menyimpan amount = harga x qty tetapi tidak mengisi total_amount, sedangkan pengeluaran manual
     * menyimpan amount = harga satuan dan total_amount = amount x qty. Samakan: total_amount selalu total baris.
     */
    public function up(): void
    {
        $typeId = DB::table('spending_types')->where('name', 'Pengadaan')->value('id');
        if (!$typeId) {
            return;
        }

        DB::table('spendings')
            ->where('spending_type_id', $typeId)
            ->where('total_amount', 0)
            ->update(['total_amount' => DB::raw('amount')]);
    }

    public function down(): void
    {
        // Tidak dikembalikan: setelah up(), baris yang sebelumnya 0 tidak bisa dibedakan dari yang memang terisi.
    }
};
