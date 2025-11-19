<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Izin;
use Carbon\Carbon;

class DashboardController extends Controller
{
   
    public function index()
    {
        $hariIni = Carbon::today();

      
        $karyawan = User::where('role', 'karyawan')
                        ->where('status', 'aktif')
                        ->orderBy('nama')
                        ->get();

        
        $absensiHariIni = Absensi::whereDate('tanggal', $hariIni)
                                ->get()
                                ->keyBy('user_id');

       
        $totalKaryawan = $karyawan->count();
        $hadir = 0;
        $terlambat = 0;
        $izinSakit = 0;
        $alpha = 0; 
        $pengajuanBaru = Izin::where('status_approval', 'Pending')->count();

      
        $monitoringHarian = [];

        foreach ($karyawan as $k) {
            // Default
            $statusTampil = 'Alpha'; 
            $badgeColor = 'danger'; 
            $jamMasuk = '-';
            $jamKeluar = '-';
            $keterangan = '-'; 
            $fotoMasuk = null;
            $statusPulang = '-'; 

          
            if ($absensiHariIni->has($k->id)) {
                $absen = $absensiHariIni[$k->id];
            
                $keterangan = $absen->ket_status_msk ?? '-';
                $fotoMasuk = $absen->foto_masuk;
                $statusDb = $absen->status_absensi; 
                $statusPulang = $absen->status_pulang ?? '-'; 

                if ($statusDb == 'Hadir') {
                    $statusTampil = 'Hadir';
                    $badgeColor = 'success'; 
                    $hadir++;
                } elseif ($statusDb == 'Terlambat') {
                    $statusTampil = 'Terlambat';
                    $badgeColor = 'warning text-dark'; 
                    $terlambat++;
                } elseif (in_array($statusDb, ['Izin', 'Sakit'])) {
                    $statusTampil = $statusDb; 
                    $badgeColor = 'info text-dark'; 
                    $izinSakit++;
                } elseif ($statusDb == 'Alpha') {
                    $statusTampil = 'Alpha';
                    $badgeColor = 'danger'; 
                    $alpha++;
                } else {
                    $alpha++;
                }
                $jamMasuk = $absen->jam_masuk ? Carbon::parse($absen->jam_masuk)->format('H:i') : '-';
                $jamKeluar = $absen->jam_keluar ? Carbon::parse($absen->jam_keluar)->format('H:i') : '-';

            } else {
            
                $alpha++;
            }

            $monitoringHarian[] = [
                'nik' => $k->nik,
                'nama' => $k->nama,
                'jabatan' => $k->jabatan,
                'status' => $statusTampil, 
                'badge' => $badgeColor,
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => $jamKeluar,
                'keterangan' => $keterangan,
                'foto_masuk' => $fotoMasuk,             
                'status_pulang' => $statusPulang, 
            ];
        }

        return view('admin.dashboard', compact(
            'totalKaryawan', 'hadir', 'terlambat', 'izinSakit', 'alpha', 'pengajuanBaru', 'monitoringHarian', 'hariIni'
        ));
    }
}