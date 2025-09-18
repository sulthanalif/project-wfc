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
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('borrower_name');
            $table->decimal('amount', 20, 2);
            $table->string('method');
            $table->foreignId('bank_owner_id')
                ->nullable()
                ->constrained('bank_owners')
                ->onDelete('cascade');
            $table->enum('status_payment', ['unpaid', 'paid', 'pending'])->default('unpaid');
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
