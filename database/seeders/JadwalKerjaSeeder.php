<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalKerja; // Import model

class JadwalKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama (jika ada) agar tidak duplikat
        JadwalKerja::truncate();

        // Data baru, jam keluar 16:00 dan toleransi 10 menit
        $jadwal = [
            ['hari' => 'Senin', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'toleransi' => 10],
            ['hari' => 'Selasa', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'toleransi' => 10],
            ['hari' => 'Rabu', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'toleransi' => 10],
            ['hari' => 'Kamis', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'toleransi' => 10],
            ['hari' => 'Jumat', 'jam_masuk' => '08:00:00', 'jam_keluar' => '16:00:00', 'toleransi' => 10],
             ['hari' => 'Sabtu', 'jam_masuk' => '00:00:00', 'jam_keluar' => '00:00:00', 'toleransi' => 0],
              ['hari' => 'Minggu', 'jam_masuk' => '00:00:00', 'jam_keluar' => '00:00:00', 'toleransi' => 0],
        ];

        // Masukkan data baru
        DB::table('jadwal_kerja')->insert($jadwal);
    }
}