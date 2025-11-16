<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Carbon\Carbon;

class AutoAbsenPulang extends Command
{
    /**
     * Nama dan signature dari command.
     */
    protected $signature = 'absensi:auto-pulang';

    /**
     * Deskripsi command.
     */
    protected $description = 'Secara otomatis melakukan absen pulang untuk karyawan yang lupa';

    /**
     * Jalankan logic command.
     */
    public function handle()
    {
        $this->info('Mengecek karyawan yang lupa absen pulang...');
        
        $today = Carbon::today();
        
        // 1. Cari semua karyawan yang sudah absen masuk HARI INI
        //    tapi BELUM absen pulang
        $karyawanLupaAbsen = Absensi::whereDate('tanggal', $today)
                                ->whereNotNull('jam_masuk') // Sudah absen masuk
                                ->whereNull('jam_keluar')   // Belum absen pulang
                                ->get();

        if ($karyawanLupaAbsen->isEmpty()) {
            $this->info('Tidak ada karyawan yang lupa absen hari ini.');
            return 0;
        }

        $this->info('Menemukan ' . $karyawanLupaAbsen->count() . ' karyawan...');

        // Waktu absen otomatis (21:00)
        $jamOtomatis = Carbon::today()->setHour(21)->setMinute(0)->setSecond(0);

        foreach ($karyawanLupaAbsen as $absen) {
            try {
                // Hitung durasi kerja dari jam masuk s/d jam 21:00
                $jamMasuk = Carbon::parse($absen->jam_masuk);
                $durasiKerja = $jamOtomatis->diff($jamMasuk)->format('%H jam %i menit');

                // Update data absensi
                $absen->update([
                    'jam_keluar' => $jamOtomatis->format('H:i:s'),
                    'durasi_bekerja' => $durasiKerja,
                    'status_pulang' => 'Diabsenkan Sistem',
                    // Kita biarkan foto_pulang NULL
                ]);

                $this->info('User ID ' . $absen->user_id . ' berhasil diabsenkan sistem.');

            } catch (\Exception $e) {
                $this->error('Gagal memproses User ID ' . $absen->user_id . ': ' . $e->getMessage());
            }
        }

        $this->info('Proses absen otomatis selesai.');
        return 0;
    }
}