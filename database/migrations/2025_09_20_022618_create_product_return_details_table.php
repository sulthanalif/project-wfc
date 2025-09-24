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
        Schema::create('product_return_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_return_id')->constrained('product_returns')->onDelete('cascade');
            $table->uuid('order_id')->constrained('orders')->onDelete('cascade');
            $table->uuid('product_id')->constrained('products')->onDelete('cascade');
            $table->uuid('sub_product_id')->constrained('sub_products')->onDelete('cascade')->nullable();
            $table->enum('status_product', ['damaged', 'expired', 'overstock', 'other']);
            $table->integer('qty')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_return_details');
    }
};
