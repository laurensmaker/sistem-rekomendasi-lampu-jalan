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
        Schema::create('hasil_survei_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_survei_id')->constrained('hasil_survei')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->unsignedTinyInteger('nilai'); // 1-5
            $table->timestamps();

            $table->unique(['hasil_survei_id', 'kriteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_survei_detail');
    }
};
