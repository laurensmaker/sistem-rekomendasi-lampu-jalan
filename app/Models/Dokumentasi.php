<?php
// app/Models/Dokumentasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi';

    protected $fillable = [
        'lokasi_id',
        'gambar',
        'keterangan',
        'foto_survei',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function hasilSurvei()
    {
        return $this->hasMany(HasilSurvei::class);
    }
}