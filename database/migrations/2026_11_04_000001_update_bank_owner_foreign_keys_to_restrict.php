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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->restrictOnDelete();
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->restrictOnDelete();
        });

        Schema::table('loan_payments', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->restrictOnDelete();
        });

        Schema::table('spendings', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->restrictOnDelete();
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->onDelete('cascade');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->onDelete('cascade');
        });

        Schema::table('loan_payments', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->onDelete('cascade');
        });

        Schema::table('spendings', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->onDelete('cascade');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['bank_owner_id']);
            $table->foreign('bank_owner_id')->references('id')->on('bank_owners')->onDelete('cascade');
        });
    }
};
