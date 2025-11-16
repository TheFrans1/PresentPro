<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Absensi;   
use App\Models\JadwalKerja; 
use App\Models\Izin;      
use Illuminate\Support\Str; 

class AbsenController extends Controller
{
    /**
     * Menampilkan dashboard dinamis untuk karyawan.
     */
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $absenHariIni = Absensi::where('user_id', $userId)
                                ->whereDate('tanggal', $today)
                                ->first();
        $izinHariIni = Izin::where('user_id', $userId)
                           ->where('status_approval', 'Disetujui')
                           ->where('tanggal_mulai', '<=', $today)
                           ->where('tanggal_selesai', '>=', $today)
                           ->first();
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $namaHariInggris = $today->format('l');
        $namaHariIni = $dayMap[$namaHariInggris]; 
        $jadwalHariIni = JadwalKerja::where('hari', $namaHariIni)->first();
        $isHariLibur = false;
        if (!$jadwalHariIni || ($jadwalHariIni->jam_masuk == null || $jadwalHariIni->jam_masuk == '00:00:00')) {
            $isHariLibur = true;
        }
        return view('karyawan.dashboard', [
            'absenHariIni' => $absenHariIni,
            'isHariLibur' => $isHariLibur,
            'izinHariIni' => $izinHariIni,
            'jadwalHariIni' => $jadwalHariIni,
        ]);
    }


    /**
     * Menyimpan data absen MASUK.
     * (DIKEMBALIKAN: Logika Keterangan dihitung dalam TOTAL MENIT)
     */
    public function storeMasuk(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $request->validate(['image' => 'required']);
        $absenHariIni = Absensi::where('user_id', $userId)->whereDate('tanggal', $today)->first();
        if ($absenHariIni) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }
        $dayMap = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
        $namaHariIni = $dayMap[$today->format('l')];
        $jadwal = JadwalKerja::where('hari', $namaHariIni)->first();
        if (!$jadwal || !$jadwal->jam_masuk || $jadwal->jam_masuk == '00:00:00') {
            return redirect()->back()->with('error', 'Tidak ada jadwal kerja aktif untuk hari ini.');
        }

        // Tentukan Status
        $jamMasukJadwal = Carbon::parse($jadwal->jam_masuk);
        $jamBatasTelat = $jamMasukJadwal->addMinutes($jadwal->toleransi ?? 0);
        $jamSekarang = Carbon::now();
        
        $status = ($jamSekarang->gt($jamBatasTelat)) ? 'Terlambat' : 'Hadir';
        
        // ================== LOGIKA KETERANGAN (TOTAL MENIT) ==================
        $keterangan = null; // Default NULL jika Hadir

        if ($status == 'Terlambat') {
            // Selisih dalam menit dari batas telat ke waktu sekarang
            $menitTelat = $jamBatasTelat->diffInMinutes($jamSekarang, false);
            // Menghilangkan nilai minus jika ada
            if ($menitTelat < 0) {
                $menitTelat = abs($menitTelat);
            }
            // Konversi ke jam dan menit
            $jam = intdiv($menitTelat, 60);        // bagi 60 untuk dapat jam
            $menit = $menitTelat % 60;            // sisa menit
            // Format keterangannya
            if ($jam > 0 && $menit > 0) {
                $keterangan = "Terlambat {$jam} jam {$menit} menit";
            } elseif ($jam > 0) {
                $keterangan = "Terlambat {$jam} jam";
            } else {
                $keterangan = "Terlambat {$menit} menit";
            }
        }

        // Proses simpan foto MASUK
        $fotoPath = $this->simpanFoto($request->input('image'), 'masuk');
        if ($fotoPath === false) {
             return redirect()->back()->with('error', 'Gagal menyimpan foto. Format tidak valid.');
        }

        // Simpan ke database
        try {
            Absensi::create([
                'user_id' => $userId,
                'tanggal' => $today,
                'jam_masuk' => $jamSekarang->format('H:i:s'),
                'foto_masuk' => $fotoPath,
                'status_masuk' => $status,
                'ket_status_msk' => $keterangan, // Simpan keterangan total menit
            ]);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($fotoPath);
            return redirect()->back()->with('error', 'Gagal menyimpan data absensi: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Absensi masuk berhasil dicatat. Status: ' . $status);
    }

    /**
     * Menyimpan data absen KELUAR.
     * (Logika ini sudah benar)
     */
    public function storeKeluar(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $request->validate(['image' => 'required']);
        $absenHariIni = Absensi::where('user_id', $userId)->whereDate('tanggal', $today)->first();
        if (!$absenHariIni || $absenHariIni->jam_keluar) {
            return redirect()->back()->with('error', 'Kondisi absen tidak valid.');
        }

        // Ambil Jadwal Kerja hari ini
        $dayMap = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
        $namaHariIni = $dayMap[$today->format('l')];
        $jadwal = JadwalKerja::where('hari', $namaHariIni)->first();

        if (!$jadwal || !$jadwal->jam_keluar || $jadwal->jam_keluar == '00:00:00') {
            return redirect()->back()->with('error', 'Tidak ada jadwal kerja aktif (pulang) untuk hari ini.');
        }

        // Tentukan Status Pulang (Sesuai permintaan Anda)
        $jamPulangJadwal = Carbon::parse($jadwal->jam_keluar); // Misal: 16:00
        $jamSekarang = Carbon::now();
        
        $statusPulang = 'Tepat Waktu'; // Default
        
        // Jika jam sekarang KURANG DARI jam pulang di jadwal
        if ($jamSekarang->lt($jamPulangJadwal)) { // lt = less than
            $statusPulang = 'Pulang Cepat';
        } 
        // Logika Lembur sudah dihapus

        // Proses simpan foto PULANG
        $fotoPath = $this->simpanFoto($request->input('image'), 'pulang');
        if ($fotoPath === false) {
             return redirect()->back()->with('error', 'Gagal menyimpan foto. Format tidak valid.');
        }
        
        // Update database
        try {
            $jamMasuk = Carbon::parse($absenHariIni->jam_masuk);
            $durasiKerja = $jamSekarang->diff($jamMasuk)->format('%H jam %i menit');

            $absenHariIni->update([
                'jam_keluar' => $jamSekarang->format('H:i:s'),
                'durasi_bekerja' => $durasiKerja,
                'foto_pulang' => $fotoPath,
                'status_pulang' => $statusPulang, // <-- Status dinamis
            ]);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($fotoPath);
            return redirect()->back()->with('error', 'Gagal menyimpan data absensi keluar: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Absensi keluar berhasil dicatat. Status: ' . $statusPulang);
    }

    /**
     * Helper function untuk menyimpan foto selfie (Base64).
     * (Logika ini sudah benar)
     */
    private function simpanFoto($dataUri, $jenis)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $data = substr($dataUri, strpos($dataUri, ',') + 1);
            $type = strtolower($type[1]); 
            if (!in_array($type, ['jpeg', 'jpg', 'png'])) { return false; }
            $data = base64_decode($data);
            if ($data === false) { return false; }
        } else {
            return false;
        }
        
        $namaUser = Auth::user()->nama;
        $namaUserSlug = Str::slug($namaUser, '_');
        $prefix = ($jenis == 'masuk') ? 'absn_msk' : 'absn_plg';
        $namaFile = $prefix . '_' . $namaUserSlug . '_' . date('Ymd-His') . '.' . $type;
        $folder = ($jenis == 'masuk') ? 'absen_masuk' : 'absen_keluar';
        $path = $folder . '/' . $namaFile; 

        Storage::disk('public')->put($path, $data);
        return $path;
    }
}