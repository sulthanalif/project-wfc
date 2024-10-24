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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('invoice_number');
            $table->decimal('pay', 15, 2);
            // $table->decimal('remaining_payment', 15, 2);
            $table->string('method');
            $table->string('bank')->nullable();
            // $table->string('installment');
            // $table->string('image')->nullable();
            $table->text('note')->nullable();
            $table->date('date');
            // $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
