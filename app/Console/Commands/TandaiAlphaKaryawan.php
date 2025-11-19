<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Absensi;
use App\Models\JadwalKerja;
use Carbon\Carbon;

class TandaiAlphaKaryawan extends Command
{
    
    protected $signature = 'absensi:tandai-alpha';

   
    protected $description = 'Mengecek dan menandai karyawan yang Alpha (bolos) pada hari kerja';

    
    public function handle()
    {
        $this->info('🤖 Mulai proses pengecekan Karyawan Alpha...');
        
        $today = Carbon::today();
        
    
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $namaHariIni = $dayMap[$today->format('l')];
        
       
        $jadwalHariIni = JadwalKerja::where('hari', $namaHariIni)->first();

   
        if (!$jadwalHariIni || $jadwalHariIni->jam_masuk == null || $jadwalHariIni->jam_masuk == '00:00:00') {
            $this->info('⛔ Hari ini adalah hari libur (Tidak ada jadwal). Tidak ada pengecekan Alpha.');
            return 0;
        }

    
        $karyawanAktifIds = User::where('role', 'karyawan')
                                ->where('status', 'aktif')
                                ->pluck('id');

        
        $karyawanAdaDataIds = Absensi::whereDate('tanggal', $today)
                                    ->pluck('user_id');

     
        $karyawanAlphaIds = $karyawanAktifIds->diff($karyawanAdaDataIds);

        if ($karyawanAlphaIds->isEmpty()) {
            $this->info('✅ Semua karyawan sudah memiliki data absensi/izin hari ini.');
            return 0;
        }

        $this->warn('⚠️ Menemukan ' . $karyawanAlphaIds->count() . ' karyawan tanpa keterangan. Menandai Alpha...');

      
        $dataUntukInsert = [];
        foreach ($karyawanAlphaIds as $userId) {
            $dataUntukInsert[] = [
                'user_id' => $userId,
                'tanggal' => $today,
                'jam_masuk' => null,
                'jam_keluar' => null,
                'foto_masuk' => null,
                'foto_pulang' => null,
         
                'status_absensi' => 'Alpha', 
                'status_pulang' => null,
                'durasi_bekerja' => null,
                'ket_status_msk' => 'Alpha / Tidak ada keterangan',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

      
        Absensi::insert($dataUntukInsert);

        $this->info('✅ Berhasil menandai ' . count($dataUntukInsert) . ' karyawan sebagai Alpha.');
        return 0;
    }
}