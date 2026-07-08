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
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_survei_id')->constrained('hasil_survei')->onDelete('cascade');
            $table->integer('jumlah_lampu');
            $table->decimal('jarak_antar_lampu', 8, 2);
            $table->decimal('total_biaya', 12, 2)->nullable();
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
