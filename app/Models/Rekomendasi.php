<?php
// app/Models/Rekomendasi.php (PERBAIKI YANG INI)
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi'; 
    
    protected $fillable = [
        'hasil_survei_id',
        'jumlah_lampu',
        'jarak_antar_lampu',
        'total_biaya',
        'status',
        'catatan',
        'tanggal_disetujui',
    ];

    public function hasilSurvei()
    {
        return $this->belongsTo(HasilSurvei::class, 'hasil_survei_id');
    }
}