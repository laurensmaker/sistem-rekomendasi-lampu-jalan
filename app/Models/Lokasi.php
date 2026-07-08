<?php
// app/Models/Lokasi.php
namespace App\Models;

use App\Models\Dokumentasi;
use App\Models\HasilSurvei;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'nama_jalan',
        'distrik',
        'latitude',
        'longitude',
    ];

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function hasilSurvei()
    {
        return $this->hasMany(HasilSurvei::class);
    }

    public function getKoordinatAttribute()
    {
        return $this->latitude . ', ' . $this->longitude;
    }

    public function getGoogleMapsLinkAttribute()
    {
        return 'https://www.google.com/maps?q=' . $this->latitude . ',' . $this->longitude;
    }
}