<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'izin'; // <-- Pastikan ini sesuai dengan nama tabel Anda

    /**
     * Kolom yang diizinkan untuk diisi secara massal (mass assignment).
     *
     * @var array
     */
    protected $guarded = []; // <-- Ini penting agar kita bisa 'create' dan 'update'

    /**
     * Mendefinisikan relasi "milik" (belongsTo) ke model User.
     * Satu data izin dimiliki oleh satu user.
     */
    public function user()
    {
        // 'user_id' adalah foreign key di tabel 'izin'
        // 'id' adalah primary key di tabel 'users'
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}