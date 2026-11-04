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
        Schema::table('product_packages', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('package_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('agent_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['agent_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_packages', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['package_id']);
        });
    }
};
