<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk lebar kolom otomatis
use Carbon\Carbon;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

// Kita gunakan 5 "Concerns" (alat bantu)
class RekapAbsensiExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    // Properti untuk menyimpan filter
    protected $request;

    // 1. Terima filter dari Controller
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // 2. Query ke Database (SAMA PERSIS DENGAN CONTROLLER)
    public function query()
    {
        // Ambil filter tanggal dari request, atau gunakan default
        $tanggalMulai = $this->request->input('tanggal_mulai', Carbon::today()->subMonth()->format('Y-m-d'));
        $tanggalSelesai = $this->request->input('tanggal_selesai', Carbon::today()->format('Y-m-d'));

        // Mulai query
        $query = Absensi::query()
                    ->with('user') // Ambil relasi user
                    ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        // Terapkan filter tambahan
        if ($this->request->filled('search_nama')) {
            $query->whereHas('user', function($q) {
                $q->where('nama', 'like', '%' . $this->request->search_nama . '%');
            });
        }
        if ($this->request->filled('search_nik')) {
            $query->whereHas('user', function($q) {
                $q->where('nik', 'like', '%' . $this->request->search_nik . '%');
            });
        }
        if ($this->request->filled('filter_status')) {
            $query->where('status_masuk', $this->request->filter_status);
        }

        // Urutkan data untuk ekspor
        return $query->orderBy('tanggal', 'asc')->orderBy('jam_masuk', 'asc');
    }

    // 3. Tentukan Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Tanggal',
            'Status Masuk',
            'Jam Masuk (WIB)',
            'Keterangan Masuk',
            'Status Pulang',
            'Jam Pulang (WIB)',
            'Durasi Bekerja',
        ];
    }

    // 4. Mapping Data (Mengubah data mentah menjadi baris Excel)
    public function map($absen): array
    {
        // $absen adalah data dari query()
        return [
            $absen->user->nik ?? '-',
            $absen->user->nama ?? 'User Dihapus',
            $absen->user->jabatan ?? '-',
            Carbon::parse($absen->tanggal)->format('d-m-Y'),
            $absen->status_masuk,
            $absen->jam_masuk ? Carbon::parse($absen->jam_masuk)->format('H:i:s') : '-',
            $absen->ket_status_msk ?? '-',
            $absen->status_pulang ?? '-',
            $absen->jam_keluar ? Carbon::parse($absen->jam_keluar)->format('H:i:s') : '-',
            $absen->durasi_bekerja ?? '-',
        ];
    }
}