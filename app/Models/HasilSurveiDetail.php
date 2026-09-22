<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSurveiDetail extends Model
{
    protected $table = 'hasil_survei_detail';

    protected $fillable = [
        'hasil_survei_id',
        'kriteria_id',
        'nilai',
    ];

    public function hasilSurvei()
    {
        return $this->belongsTo(HasilSurvei::class, 'hasil_survei_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
