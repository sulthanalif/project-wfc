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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_id')->constrained('spending_types')->onDelete('cascade');
            $table->decimal('pay', 20, 2);
            $table->date('date');
            $table->string('method');
            $table->foreignId('bank_owner_id')
                ->nullable()
                ->constrained('bank_owners')
                ->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
