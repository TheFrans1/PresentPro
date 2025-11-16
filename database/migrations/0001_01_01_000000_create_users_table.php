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
        // 1. Membuat tabel 'users' dengan struktur FINAL
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            
            // ===================================
            // == KOLOM EMAIL BARU DITAMBAHKAN ==
            // ===================================
            $table->string('email')->unique(); // Wajib ada dan unik
            
            $table->string('nik', 4)->unique()->nullable(); 
            $table->string('username', 50)->unique(); 
            
            $table->enum('jabatan', [
                'Divisi IT', 
                'Keuangan', 
                'HRD', 
                'Pemasaran', 
                'Operasional', 
                'Administrator'
            ]);
            
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            
            $table->string('password');
            $table->string('foto', 255)->nullable()->default('default.png');
            $table->enum('role', ['admin', 'karyawan']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Membuat tabel 'password_reset_tokens'
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Membuat tabel 'sessions'
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};