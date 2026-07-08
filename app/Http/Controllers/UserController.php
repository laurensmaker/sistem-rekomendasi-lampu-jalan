<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Role Check Methods
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

    // Helper untuk mendapatkan dashboard route berdasarkan role
    public function getDashboardRoute()
    {
        $routes = [
            'admin' => 'dashboard.admin',
            'petugas_survei' => 'dashboard.petugas',
            'staf_perencana' => 'dashboard.staf',
            'kepala_bidang' => 'dashboard.kepala',
        ];

        return $routes[$this->role] ?? 'dashboard.admin';
    }

    // Helper untuk mendapatkan role label
    public function getRoleLabelAttribute()
    {
        $labels = [
            'admin' => 'Administrator',
            'petugas_survei' => 'Petugas Survei',
            'staf_perencana' => 'Staf Perencana',
            'kepala_bidang' => 'Kepala Bidang',
        ];

        return $labels[$this->role] ?? $this->role;
    }

    // Helper untuk mendapatkan role badge color
    public function getRoleBadgeColorAttribute()
    {
        $colors = [
            'admin' => 'danger',
            'petugas_survei' => 'info',
            'staf_perencana' => 'warning',
            'kepala_bidang' => 'success',
        ];

        return $colors[$this->role] ?? 'secondary';
    }

    // Relasi
    public function hasilSurvei()
    {
        return $this->hasMany(HasilSurvei::class);
    }
}