<?php
// app/Models/RankingSaw.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingSaw extends Model
{
    use HasFactory;

    protected $table = 'ranking_saw';

    protected $fillable = [
        'hasil_survei_id',
        'kriteria_id',
        'nilai_normalisasi',
        'nilai_terbobot',
    ];

    public function hasilSurvei()
    {
        return $this->belongsTo(HasilSurvei::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}