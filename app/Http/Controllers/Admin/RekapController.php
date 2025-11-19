<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Izin;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Symfony\Component\HttpFoundation\StreamedResponse;

// Impor Library Paginasi
use Illuminate\Pagination\LengthAwarePaginator;

class RekapController extends Controller
{
    /**
     * Menampilkan Halaman Laporan Data Mentah (HIBRIDA)
     */
    public function index(Request $request)
    {
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->format('Y-m-d'));
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->subMonth()->format('Y-m-d'));

        // 1. Ambil data Absensi
        $queryAbsensi = Absensi::query()
            ->with('user')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->whereNotIn('status_absensi', ['Izin', 'Sakit']); // Pastikan nama kolom 'status_masuk' sesuai DB

        // 2. Ambil data Izin
        $queryIzin = Izin::query()
            ->with('user')
            ->where('status_approval', 'Disetujui')
            ->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where('tanggal_mulai', '<=', $tanggalSelesai)
                  ->where('tanggal_selesai', '>=', $tanggalMulai);
            });

        // 3. Filter Tambahan
        if ($request->filled('search_nama')) {
            $queryAbsensi->whereHas('user', function ($q) use ($request) { $q->where('nama', 'like', '%' . $request->search_nama . '%'); });
            $queryIzin->whereHas('user', function ($q) use ($request) { $q->where('nama', 'like', '%' . $request->search_nama . '%'); });
        }
        if ($request->filled('search_nik')) {
            $queryAbsensi->whereHas('user', function ($q) use ($request) { $q->where('nik', 'like', '%' . $request->search_nik . '%'); });
            $queryIzin->whereHas('user', function ($q) use ($request) { $q->where('nik', 'like', '%' . $request->search_nik . '%'); });
        }
        if ($request->filled('filter_status')) {
            $status = $request->filter_status;
            if (in_array($status, ['Hadir', 'Terlambat', 'Alpha'])) {
                $queryAbsensi->where('status_absensi', $status);
                $queryIzin->where('id', -1); 
            } elseif (in_array($status, ['Izin', 'Sakit'])) {
                $queryIzin->where('jenis', $status);
                $queryAbsensi->where('id', -1);
            }
        }

        // 4. Ambil Hasil (DENGAN PERBAIKAN toBase)
        
        // --- PERBAIKAN UTAMA DI SINI: toBase() ---
        // Kita ubah menjadi koleksi dasar agar bisa digabung dengan stdClass tanpa error getKey()
        $dataAbsensi = $queryAbsensi->get()->toBase(); 
        $dataIzin = $queryIzin->get();
        
        // 5. Format Data Izin
        $dataIzinFormatted = $dataIzin->map(function($izin) use ($tanggalMulai, $tanggalSelesai) {
            $rows = [];
            $period = CarbonPeriod::create(
                max($izin->tanggal_mulai, $tanggalMulai), 
                min($izin->tanggal_selesai, $tanggalSelesai)
            );
            
            foreach ($period as $date) {
                $rows[] = (object) [ 
                    'user_id' => $izin->user_id,
                    'user' => $izin->user, // Relasi user tetap terbawa
                    'tanggal' => $date->format('Y-m-d'),
                    'status_absensi' => $izin->jenis, 
                    'ket_status_msk' => $izin->keterangan,
                    
                    'is_izin' => true, 
                    'izin_tanggal_mulai' => $izin->tanggal_mulai,
                    'izin_tanggal_selesai' => $izin->tanggal_selesai,
                    'izin_file_bukti' => $izin->file_bukti,
                    'izin_status_approval' => $izin->status_approval,
                    'izin_tanggal_pengajuan' => $izin->tanggal_pengajuan,

                    'jam_masuk' => null, 'foto_masuk' => null,
                    'jam_keluar' => null, 'foto_pulang' => null,
                    'status_pulang' => null, 'durasi_bekerja' => null,
                ];
            }
            return $rows;
        })->flatten(); 

        // 6. Gabungkan & Urutkan
        $mergedData = $dataAbsensi->merge($dataIzinFormatted)
                                ->sortByDesc('tanggal')
                                ->values(); // Reset keys

        // 7. Paginasi Manual
        $perPage = 25;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $mergedData->slice(($currentPage - 1) * $perPage, $perPage);
        
        $dataLaporan = new LengthAwarePaginator(
            $currentPageItems,
            $mergedData->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()] 
        );

        return view('admin.rekap.index', [
            'dataLaporan' => $dataLaporan,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'request' => $request->all(),
        ]);
    }

    /**
     * FUNGSI EKSPOR CSV
     */
    public function exportExcel(Request $request)
    {
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->format('Y-m-d'));
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->subMonth()->format('Y-m-d'));
        $namaFile = 'rekap_absensi_' . $tanggalMulai . '_sd_' . $tanggalSelesai . '.csv';

        $queryAbsensi = Absensi::query()->with('user')->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->whereNotIn('status_absensi', ['Izin', 'Sakit']);
        $queryIzin = Izin::query()->with('user')->where('status_approval', 'Disetujui')->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where('tanggal_mulai', '<=', $tanggalSelesai)->where('tanggal_selesai', '>=', $tanggalMulai);
            });
        
        if ($request->filled('search_nama')) {
            $queryAbsensi->whereHas('user', function ($q) use ($request) { $q->where('nama', 'like', '%' . $request->search_nama . '%'); });
            $queryIzin->whereHas('user', function ($q) use ($request) { $q->where('nama', 'like', '%' . $request->search_nama . '%'); });
        }
        if ($request->filled('search_nik')) {
            $queryAbsensi->whereHas('user', function ($q) use ($request) { $q->where('nik', 'like', '%' . $request->search_nik . '%'); });
            $queryIzin->whereHas('user', function ($q) use ($request) { $q->where('nik', 'like', '%' . $request->search_nik . '%'); });
        }
        if ($request->filled('filter_status')) {
            $status = $request->filter_status;
            if (in_array($status, ['Hadir', 'Terlambat', 'Alpha'])) {
                $queryAbsensi->where('status_absensi', $status);
                $queryIzin->where('id', -1); 
            } elseif (in_array($status, ['Izin', 'Sakit'])) {
                $queryIzin->where('jenis', $status);
                $queryAbsensi->where('id', -1);
            }
        }

        // PENTING: Gunakan toBase() juga di sini untuk konsistensi
        $dataAbsensi = $queryAbsensi->get()->toBase();
        $dataIzin = $queryIzin->get();
        
        $dataIzinFormatted = $dataIzin->map(function($izin) use ($tanggalMulai, $tanggalSelesai) {
            $rows = [];
            $period = CarbonPeriod::create(max($izin->tanggal_mulai, $tanggalMulai), min($izin->tanggal_selesai, $tanggalSelesai));
            foreach ($period as $date) {
                $rows[] = (object) [
                    'user_id' => $izin->user_id, 'user' => $izin->user, 'tanggal' => $date->format('Y-m-d'),
                    'status_absensi' => $izin->jenis, 'ket_status_msk' => $izin->keterangan,
                    'is_izin' => true, 'izin_tanggal_mulai' => $izin->tanggal_mulai, 'izin_tanggal_selesai' => $izin->tanggal_selesai,
                    'izin_file_bukti' => $izin->file_bukti, 'izin_status_approval' => $izin->status_approval, 'izin_tanggal_pengajuan' => $izin->tanggal_pengajuan,
                    'jam_masuk' => null, 'foto_masuk' => null, 'jam_keluar' => null, 'foto_pulang' => null,
                    'status_pulang' => null, 'durasi_bekerja' => null,
                ];
            }
            return $rows;
        })->flatten();

        $dataLaporan = $dataAbsensi->merge($dataIzinFormatted)->sortByDesc('tanggal');
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
        ];

        return new StreamedResponse(function() use ($dataLaporan) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'NIK', 'Nama Karyawan', 'Jabatan', 
                'Tanggal Absen', 'Status Masuk', 'Jam Masuk (WIB)', 'Keterangan Masuk', 'Foto Masuk (Link)',
                'Status Pulang', 'Jam Pulang (WIB)', 'Durasi Bekerja', 'Foto Pulang (Link)',
                'Tgl Pengajuan Izin', 'Status Approval', 'File Bukti Izin (Link)'
            ]);

            foreach ($dataLaporan as $data) {
                $isIzin = isset($data->is_izin) && $data->is_izin;
                $linkFoto = [];
                if (!$isIzin && $data->foto_masuk) { $linkFoto[] = "Masuk: " . asset('storage/' . $data->foto_masuk); }
                if (!$isIzin && $data->foto_pulang) { $linkFoto[] = "Pulang: " . asset('storage/' . $data->foto_pulang); }
                $fotoGabungan = empty($linkFoto) ? '-' : implode("\n", $linkFoto);

                fputcsv($handle, [
                    $data->user->nik ?? '-',
                    $data->user->nama ?? 'User Dihapus',
                    $data->user->jabatan ?? '-',
                    Carbon::parse($data->tanggal)->format('d-m-Y'),
                    $data->status_absensi,
                    $data->jam_masuk ? Carbon::parse($data->jam_masuk)->format('H:i:s') : '-',
                    $data->ket_status_msk ?? '-',
                    $data->foto_masuk ?? '-',
                    $data->status_pulang ?? '-',
                    $data->jam_keluar ? Carbon::parse($data->jam_keluar)->format('H:i:s') : '-',
                    $data->durasi_bekerja ?? '-',
                    $data->foto_pulang ?? '-',
                    isset($data->is_izin) ? Carbon::parse($data->izin_tanggal_pengajuan)->format('d-m-Y H:i') : '-',
                    isset($data->is_izin) ? $data->izin_status_approval : '-',
                    isset($data->is_izin) ? asset('storage/' . $data->izin_file_bukti) : '-',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}