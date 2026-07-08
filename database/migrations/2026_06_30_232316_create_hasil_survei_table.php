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
        Schema::create('hasil_survei', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('dokumentasi_id')->constrained('dokumentasi')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_survei');
            
            // Data untuk kriteria (C1-C5)
            $table->integer('kepadatan_penduduk')->default(0); // C1 - 1-5
            $table->integer('volume_lalu_lintas')->default(0); // C2 - 1-5
            $table->integer('aktivitas_malam')->default(0); // C3 - 1-5
            $table->integer('penerangan_saat_ini')->default(0); // C4 - 1-5
            $table->integer('kerawanan_kecelakaan')->default(0); // C5 - 1-5
            
            // Data tambahan untuk perhitungan LPJU
            $table->decimal('panjang_jalan', 10, 2)->default(0);
            $table->decimal('tinggi_tiang', 10, 2)->default(0);
            $table->decimal('lebar_jalan', 10, 2)->default(0);
            
            // Nilai preferensi (hasil SAW)
            $table->decimal('nilai_preferensi', 10, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_survei');
    }
};
