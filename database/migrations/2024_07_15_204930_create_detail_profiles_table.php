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
        Schema::create('detail_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('titleHistory');
            $table->text('bodyHistory');
            $table->string('image');
            $table->string('titleVM');
            $table->string('vision');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_profiles');
    }
};
