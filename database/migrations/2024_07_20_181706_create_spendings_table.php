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
        Schema::create('spendings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('information')->nullable();
            $table->uuid('spending_type_id')->constrained('spending_types')->onDelete('cascade');;
            $table->decimal('amount', 20, 2);
            $table->string('method');
            $table->string('bank')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spendings');
    }
};
