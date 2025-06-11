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
        Schema::table('distributions', function (Blueprint $table) {
            $table->enum('status', ['on_process', 'delivered', 'cancelled'])->default('on_process');
            $table->boolean('is_delivery')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('is_delivery');
        });
    }
};
