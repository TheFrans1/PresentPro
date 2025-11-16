<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama class ini akan otomatis dibuat oleh 'make:migration'
// Sesuaikan dengan nama class di file Anda jika berbeda
class CreateAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ini adalah skema final kita
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            
            // Kolom foto yang sudah benar
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();
            
            // Kolom status yang sudah benar (status -> status_masuk)
            $table->enum('status_masuk', ['Hadir', 'Terlambat', 'Alpha', 'Izin', 'Sakit'])->nullable();
            $table->enum('status_pulang', ['Tepat Waktu', 'Pulang Cepat', 'Diabsenkan Sistem'])->nullable();
            
            $table->string('durasi_bekerja', 20)->nullable();
            $table->text('ket_status_msk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensis');
    }
}