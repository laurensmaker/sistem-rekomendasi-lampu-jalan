<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
   
     public function hasilSurvei()
    {
        return $this->hasMany(HasilSurvei::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugasSurvei()
    {
        return $this->role === 'petugas_survei';
    }

    public function isStafPerencana()
    {
        return $this->role === 'staf_perencana';
    }

    public function isKepalaBidang()
    {
        return $this->role === 'kepala_bidang';
    }
}
