<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('izin', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->enum('jenis', ['Izin', 'Sakit']);
    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai');
    $table->text('keterangan');
    $table->string('file_bukti', 255);
    $table->enum('status_approval', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
    $table->timestamp('tanggal_pengajuan')->useCurrent();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
