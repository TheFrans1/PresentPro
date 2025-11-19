<?php

// File: ..._create_jadwal_kerja_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('hari', 20); 
            $table->time('jam_masuk'); 
            $table->time('jam_keluar'); 
            $table->integer('toleransi')->default(10); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};