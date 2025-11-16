<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel jika tidak jamak 's' (opsional tapi bagus)
     */
    protected $table = 'absensi';

    /**
     * Kolom yang boleh diisi oleh create() atau update()
     */
    protected $fillable = [
       'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'foto_masuk',   
        'foto_pulang',   
        'status_masuk',
        'status_pulang', 
        'durasi_bekerja',
        'ket_status_msk',
    ];

    /**
     * Relasi ke User (Karyawan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}