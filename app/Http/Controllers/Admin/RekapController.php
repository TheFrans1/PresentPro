<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse; // Untuk Ekspor CSV

class RekapController extends Controller
{
    /**
     * Menampilkan Halaman Laporan Data Mentah (Filter)
     * (PERBAIKAN: Menggunakan 'status_absen' untuk filter)
     */
    public function index(Request $request)
    {
        // Tetapkan rentang tanggal default: 1 bulan terakhir
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->format('Y-m-d'));
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->subMonth()->format('Y-m-d'));

        // 1. Ambil data absensi berdasarkan rentang tanggal
        $query = Absensi::query()
                    ->with('user') // Ambil relasi ke tabel User (untuk NIK, Nama, Jabatan)
                    ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        // 2. Terapkan filter tambahan jika ada
        if ($request->filled('search_nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search_nama . '%');
            });
        }
        
        if ($request->filled('search_nik')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nik', 'like', '%' . $request->search_nik . '%');
            });
        }
        
        // ================== PERBAIKAN DI SINI ==================
        if ($request->filled('filter_status')) {
            $query->where('status_absensi', $request->filter_status); // Diganti dari 'status_masuk'
        }
        // =======================================================

        // 3. Ambil data, urutkan, dan paginasi
        $dataLaporan = $query->orderBy('tanggal', 'desc')
                            ->orderBy('jam_masuk', 'desc')
                            ->paginate(25); // Tampilkan 25 data per halaman

        // 4. Kirim data ke View
        return view('admin.rekap.index', [
            'dataLaporan' => $dataLaporan,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'request' => $request->all(), // Kirim filter untuk Paginasi
        ]);
    }

    /**
     * Menangani permintaan ekspor CSV.
     * (PERBAIKAN: Menggunakan 'status_absen' untuk filter dan ekspor)
     */
    public function exportExcel(Request $request)
    {
        // 1. Tentukan nama file
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->subMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->format('Y-m-d'));
        $namaFile = 'rekap_absensi_' . $tanggalMulai . '_sd_' . $tanggalSelesai . '.csv';

        // 2. Ambil data (Logika query SAMA PERSIS dengan fungsi index)
        $query = Absensi::query()
                    ->with('user')
                    ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($request->filled('search_nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search_nama . '%');
            });
        }
        if ($request->filled('search_nik')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nik', 'like', '%' . $request->search_nik . '%');
            });
        }
        
        // ================== PERBAIKAN DI SINI ==================
        if ($request->filled('filter_status')) {
            $query->where('status_absensi', $request->filter_status); // Diganti dari 'status_masuk'
        }
        // =======================================================

        // Ambil SEMUA data yang difilter, JANGAN paginate
        $dataLaporan = $query->orderBy('tanggal', 'asc')
                             ->orderBy('jam_masuk', 'asc')
                             ->get();

        // 3. Siapkan header HTTP untuk download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
        ];

        // 4. Buat file CSV "on the fly" (streaming)
        return new StreamedResponse(function() use ($dataLaporan) {
            $handle = fopen('php://output', 'w');

            // Tulis Judul Kolom (Header)
            fputcsv($handle, [
                'NIK',
                'Nama Karyawan',
                'Jabatan',
                'Tanggal',
                'Status Absensi', // Judul kolom di Excel tetap 'Status Masuk'
                'Jam Masuk (WIB)',
                'Keterangan Masuk',
                'Status Pulang',
                'Jam Pulang (WIB)',
                'Durasi Bekerja',
            ]);

            // Tulis Data Baris per Baris
            foreach ($dataLaporan as $absen) {
                fputcsv($handle, [
                    $absen->user->nik ?? '-',
                    $absen->user->nama ?? 'User Dihapus',
                    $absen->user->jabatan ?? '-',
                    Carbon::parse($absen->tanggal)->format('d-m-Y'),
                    // ================== PERBAIKAN DI SINI ==================
                    $absen->status_absensi, // Diganti dari 'status_masuk'
                    // =======================================================
                    $absen->jam_masuk ? Carbon::parse($absen->jam_masuk)->format('H:i:s') : '-',
                    $absen->ket_status_msk ?? '-',
                    $absen->status_pulang ?? '-',
                    $absen->jam_keluar ? Carbon::parse($absen->jam_keluar)->format('H:i:s') : '-',
                    $absen->durasi_bekerja ?? '-',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}