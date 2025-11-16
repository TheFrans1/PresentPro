<?php
// File: app/Models/JadwalKerja.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ... di dalam file app/Models/JadwalKerja.php

class JadwalKerja extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kerja'; // Opsional, tapi bagus

    protected $fillable = [
        'hari',
        'jam_masuk',
        'jam_keluar',
        'toleransi'    // <-- PASTIKAN INI ADA
    ];
}