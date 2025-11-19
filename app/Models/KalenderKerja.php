<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalenderKerja extends Model
{
    use HasFactory;

    
    protected $table = 'kalender_kerja';

    protected $guarded = [];
}