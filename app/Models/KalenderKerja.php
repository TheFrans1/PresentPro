<?php
// File: app/Models/KalenderKerja.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalenderKerja extends Model
{
    use HasFactory;

    // Nama tabel-nya 'kalender_kerja'
    protected $table = 'kalender_kerja';

    // Izinkan Mass Assignment
    protected $guarded = [];
}