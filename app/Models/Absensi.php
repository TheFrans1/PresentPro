<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    
    protected $table = 'absensi';

    
    protected $fillable = [
       'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'foto_masuk',   
        'foto_pulang',   
        'status_absensi',
        'status_pulang', 
        'durasi_bekerja',
        'ket_status_msk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function izin()
    {
         return $this->hasOne(Izin::class, 'user_id', 'user_id');
    }
}