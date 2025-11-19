<?php
// File: app/Models/JadwalKerja.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kerja'; 

    protected $fillable = [
        'hari',
        'jam_masuk',
        'jam_keluar',
        'toleransi'   
    ];
}