<?php

// File: ..._create_kalender_kerja_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini menandai hari libur spesifik
        Schema::create('kalender_kerja', function (Blueprint $table) {
            $table->id();
            // Tanggal yang libur
            $table->date('tanggal')->unique(); 
            // Keterangan (Misal: 'Tahun Baru', 'Cuti Bersama Idul Fitri')
            $table->string('keterangan'); 
            // Jenis liburnya
            $table->enum('tipe', ['Libur Nasional', 'Cuti Bersama']); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kalender_kerja');
    }
};