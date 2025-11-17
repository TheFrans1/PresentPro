<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- Pastikan Request di-import

// --- Impor Model & Library yang kita butuhkan ---
use App\Models\Izin;
use App\Models\Absensi;
use App\Models\JadwalKerja;
use App\Models\User; // <-- Pastikan User di-import
use Illuminate\Support\Facades\DB; // Untuk Database Transaction
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Untuk menangani izin multi-hari

class IzinController extends Controller
{
    /**
     * Menampilkan daftar pengajuan izin yang 'Pending'.
     * (Versi terbaru dengan filter dan pagination)
     */
    public function index(Request $request) // <-- Tambahkan Request
    {
        // Mulai query
        $query = Izin::query()
                    ->where('status_approval', 'Pending')
                    ->join('users', 'izin.user_id', '=', 'users.id') // Join untuk filter
                    ->select('izin.*'); // Pilih kolom dari tabel izins

        // --- Terapkan Filter ---
        if ($request->filled('search')) {
            $query->where('users.nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('filter_nik')) {
            $query->where('users.nik', 'like', '%' . $request->filter_nik . '%'); 
        }
        if ($request->filled('filter_tanggal')) {
            try {
                // Asumsi format 'd-m-Y' dari datepicker
                $tanggal = Carbon::createFromFormat('d-m-Y', $request->filter_tanggal)->format('Y-m-d');
                $query->whereDate('izin.tanggal_pengajuan', $tanggal);
            } catch (\Exception $e) { /* Abaikan tanggal tidak valid */ }
        }
        
        // Ambil data dengan 'user' dan PAGINATE
        $izinPending = $query->with('user') 
                        ->orderBy('izin.tanggal_pengajuan', 'desc')
                        ->paginate(10); // <-- Gunakan paginate()
        
        // Tambahkan parameter filter ke link pagination
        $izinPending->appends($request->all());

        return view('admin.izin.index', compact('izinPending')); 
    }

    /**
     * Menampilkan riwayat semua izin (Disetujui / Ditolak).
     * (Versi terbaru dengan filter dan pagination)
     */
    public function riwayat(Request $request) // <-- Tambahkan Request
    {
         // Mulai query
         $query = Izin::query()
                    ->whereIn('status_approval', ['Disetujui', 'Ditolak'])
                    ->join('users', 'izin.user_id', '=', 'users.id') // Join untuk filter
                    ->select('izin.*'); // Pilih kolom dari tabel izins

        // --- Terapkan Filter ---
        if ($request->filled('search')) {
            $query->where('users.nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('filter_nik')) {
            $query->where('users.nik', 'like', '%' . $request->filter_nik . '%'); 
        }
        if ($request->filled('filter_tanggal')) {
            try {
                $tanggal = Carbon::createFromFormat('d-m-Y', $request->filter_tanggal)->format('Y-m-d');
                $query->whereDate('izin.tanggal_pengajuan', $tanggal);
            } catch (\Exception $e) { /* Abaikan tanggal tidak valid */ }
        }
        if ($request->filled('filter_status')) {
            $query->where('izin.status_approval', $request->filter_status);
        }

         // Ambil data dengan 'user' dan PAGINATE
         $izinRiwayat = $query->with('user')
                        ->orderBy('izin.tanggal_pengajuan', 'desc')
                        ->paginate(10); // <-- Gunakan paginate()

        // Tambahkan parameter filter ke link pagination
        $izinRiwayat->appends($request->all());
                        
        return view('admin.izin.riwayat', compact('izinRiwayat')); 
    }


    /**
     * ==========================================================
     * FUNGSI "JEMBATAN" (SETUJUI)
     * ==========================================================
     * Menyetujui pengajuan izin DAN membuat record di tabel absensi.
     */
    public function setujui(Izin $izin)
    {
        // Peta hari (untuk cek jadwal kerja)
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];

        // 1. Mulai Transaksi Database
        // Ini penting agar jika salah satu gagal, semua dibatalkan
        DB::beginTransaction();
        try {
            // 2. Setujui di tabel 'izins'
            $izin->update(['status_approval' => 'Disetujui']);

            // 3. Buat periode tanggal (Menangani izin multi-hari)
            $period = CarbonPeriod::create($izin->tanggal_mulai, $izin->tanggal_selesai);

            // 4. Loop untuk setiap hari dalam rentang izin
            foreach ($period as $date) {
                
                // 5. Cek apakah hari ini adalah hari kerja
                $namaHariInggris = $date->format('l');
                $namaHariIni = $dayMap[$namaHariInggris];
                $jadwalHariIni = JadwalKerja::where('hari', $namaHariIni)->first();

                // Hanya buat record absensi jika itu adalah HARI KERJA
                if ($jadwalHariIni && $jadwalHariIni->jam_masuk != null && $jadwalHariIni->jam_masuk != '00:00:00') {
                    
                    // 6. INI ADALAH "JEMBATAN" YANG HILANG
                    // Buat/Update "jembatan" di tabel 'absensis'
                    Absensi::updateOrCreate(
                        [
                            'user_id' => $izin->user_id,
                            'tanggal' => $date->format('Y-m-d')
                        ],
                        [
                            'status_absensi' => $izin->jenis, // 'Izin' atau 'Sakit' (sesuai db)
                            'ket_status_msk' => $izin->keterangan, // (sesuai db)
                            'jam_masuk' => null,
                            'jam_keluar' => null,
                            'foto_masuk' => null,
                            'foto_pulang' => null,
                            'durasi_bekerja' => null, // (sesuai db)
                            'status_pulang' => null
                        ]
                    );
                }
            }
            
            // 7. Jika semua berhasil, commit
            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan izin berhasil disetujui dan dicatat di rekap absensi.');

        } catch (\Exception $e) {
            // 8. Jika ada error, batalkan semua
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui izin: ' . $e->getMessage());
        }
    }

    /**
     * Menolak pengajuan izin.
     */
    public function tolak(Izin $izin)
    {
        // 1. Ubah status di tabel izin
        $izin->update(['status_approval' => 'Ditolak']);
        
        // 2. (OPSIONAL: Hapus data di absensi JIKA sebelumnya pernah disetujui)
        // Ini untuk membersihkan jika admin salah klik "Setujui" lalu "Tolak"
        Absensi::where('user_id', $izin->user_id)
               ->where('tanggal', '>=', $izin->tanggal_mulai)
               ->where('tanggal', '<=', $izin->tanggal_selesai)
               ->whereIn('status_masuk', ['Izin', 'Sakit']) // (sesuai db)
               ->delete();

        return redirect()->back()->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}