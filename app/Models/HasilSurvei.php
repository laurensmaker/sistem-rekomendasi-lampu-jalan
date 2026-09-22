<?php
// app/Models/HasilSurvei.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSurvei extends Model
{
    use HasFactory;

    protected $table = 'hasil_survei';

    protected $fillable = [
        'lokasi_id',
        'dokumentasi_id',
        'user_id',
        'tanggal_survei',
        'kepadatan_penduduk',
        'volume_lalu_lintas',
        'aktivitas_malam',
        'penerangan_saat_ini',
        'kerawanan_kecelakaan',
        'panjang_jalan',
        'tinggi_tiang',
        'lebar_jalan',
        'nilai_preferensi',
    ];



    protected $casts = [
        'tanggal_survei' => 'date',
        'nilai_preferensi' => 'decimal:4',
    ];

    public function details()
    {
        return $this->hasMany(HasilSurveiDetail::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function dokumentasi()
    {
        return $this->belongsTo(Dokumentasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rankingSaw()
    {
        return $this->hasMany(RankingSaw::class);
    }

    public function rekomendasi()
    {
        return $this->hasOne(Rekomendasi::class);
    }

     public function getNilaiKriteria($kriteriaId)
    {
        return $this->details->firstWhere('kriteria_id', $kriteriaId)?->nilai;
    }

    public function getNilaiByNama($namaKriteria)
    {
        $kriteria = \App\Models\Kriteria::where('nama_kriteria', $namaKriteria)->first();
        return $kriteria ? $this->getNilaiKriteria($kriteria->id) : null;
    }
}