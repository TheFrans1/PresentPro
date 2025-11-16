<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\JadwalKerja;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Kita pakai DB untuk efisiensi

class RekapController extends Controller
{
    /**
     * Menampilkan Halaman Monitoring Harian (Bukan Bulanan)
     * Sesuai permintaan baru.
     */
    public function index(Request $request)
    {
        // 1. Tentukan HARI INI (sesuai timezone Anda di config/app.php)
        $hariIni = Carbon::today();
        
        // 2. Ambil Jadwal Kerja HARI INI
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $namaHariIni = $dayMap[$hariIni->format('l')];
        $jadwalHariIni = JadwalKerja::where('hari', $namaHariIni)->first();

        // Cek apakah hari ini hari kerja
        $isHariKerja = ($jadwalHariIni && $jadwalHariIni->jam_masuk != null && $jadwalHariIni->jam_masuk != '00:00:00');

        // 3. Ambil semua Karyawan Aktif
        $karyawanList = User::where('role', 'karyawan')
                            ->where('status', 'aktif')
                            ->orderBy('nama')
                            ->get();
        
        // 4. Ambil semua data Absensi HARI INI
        // keyBy('user_id') agar mudah dicari
        $absensiHariIni = Absensi::whereDate('tanggal', $hariIni)
                                ->get()
                                ->keyBy('user_id');

        // 5. Siapkan Data Monitoring (Logika Utama)
        $monitoringData = [];
        $totals = [
            'Hadir' => 0,
            'Terlambat' => 0,
            'Izin' => 0,
            'Sakit' => 0,
            'Alpha' => 0,
            'Libur' => 0,
        ];

        foreach ($karyawanList as $karyawan) {
            $status = 'Alpha'; // Default status (sesuai permintaan Anda)
            $detail = null;    // Detail (jam masuk/keterangan)

            // Cek data absensi hari ini untuk karyawan ini
            $absen = $absensiHariIni->get($karyawan->id);

            if ($absen) {
                // Karyawan ada di tabel absensis (Hadir, Terlambat, Izin, atau Sakit)
                $status = $absen->status_masuk; // 'Hadir', 'Terlambat', 'Izin', 'Sakit'
                
                if ($status == 'Hadir') {
                    $detail = 'Masuk: ' . Carbon::parse($absen->jam_masuk)->format('H:i');
                    $totals['Hadir']++;
                } elseif ($status == 'Terlambat') {
                    $detail = $absen->ket_status_msk; // "Terlambat X jam Y menit"
                    $totals['Terlambat']++;
                } elseif ($status == 'Izin') {
                    $detail = $absen->keterangan;
                    $totals['Izin']++;
                } elseif ($status == 'Sakit') {
                    $detail = $absen->keterangan;
                    $totals['Sakit']++;
                }
            
            } elseif (!$isHariKerja) {
                // Karyawan tidak ada di tabel absensi, TAPI hari ini Libur
                $status = 'Libur';
                $detail = 'Hari Libur';
                $totals['Libur']++;
            } else {
                // Karyawan tidak ada di tabel absensi, dan hari ini Hari Kerja
                // Status tetap 'Alpha' (dari default)
                $totals['Alpha']++;
            }

            // Masukkan data ke array
            $monitoringData[] = [
                'nik' => $karyawan->nik,
                'nama' => $karyawan->nama,
                'jabatan' => $karyawan->jabatan,
                'status' => $status,
                'detail' => $detail,
            ];
        }

        // 6. Kirim data ke View
        return view('admin.rekap.index', [
            'monitoringData' => $monitoringData, // Data yang sudah diolah
            'totals' => $totals,                 // Total hitungan
            'tanggalHariIni' => $hariIni,
        ]);
    }
}