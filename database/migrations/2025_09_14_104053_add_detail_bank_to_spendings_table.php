<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spendings', function (Blueprint $table) {
            $table->foreignId('bank_owner_id')
                ->nullable()
                ->constrained('bank_owners')
                ->onDelete('cascade');
            $table->decimal('total_amount', 20, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spendings', function (Blueprint $table) {
            $table->dropColumn('bank_owner_id');
            $table->dropColumn('total_amount');
        });
    }
};
