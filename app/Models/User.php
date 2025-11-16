<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * ==========================================================
     * == EMAIL DITAMBAHKAN KE $fillable ==
     * ==========================================================
     * Atribut yang diizinkan untuk diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email', // <-- TAMBAHKAN INI
        'nik',
        'username',
        'jabatan',
        'alamat',
        'no_hp',
        'password',
        'role',
        'status',
        'foto',
    ];
    // ==========================================================
    // == AKHIR PERBAIKAN ==
    // ==========================================================


    /**
     * Atribut yang harus disembunyikan (hidden).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi: Satu User bisa memiliki BANYAK data absensi.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Relasi: Satu User bisa memiliki BANYAK data izin.
     */
    public function izin()
    {
        return $this->hasMany(Izin::class);
    }
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}