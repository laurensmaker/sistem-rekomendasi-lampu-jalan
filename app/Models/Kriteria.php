<?php
// app/Models/Kriteria.php
namespace App\Models;

use App\Models\RankingSaw;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'bobot',
        'atribut',
    ];

    public function rankingSaw()
    {
        return $this->hasMany(RankingSaw::class);
    }
}