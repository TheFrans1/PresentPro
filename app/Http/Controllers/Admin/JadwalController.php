<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKerja;
// Kita TIDAK PERLU 'use Carbon\Carbon;' lagi

class JadwalController extends Controller
{
    /**
     * Menampilkan halaman pengaturan jadwal kerja.
     */
    public function index()
    {
        $jadwalKerja = JadwalKerja::orderBy('id')->get(); 
        return view('admin.jadwal.index', compact('jadwalKerja'));
    }

    /**
     * Menyimpan atau memperbarui data jadwal kerja.
     */
    public function update(Request $request)
    {
        // 1. Validasi: Kembali ke format 24-jam (H:i)
        $request->validate([
            'jam_masuk.*' => 'nullable|date_format:H:i',
            'jam_keluar.*' => 'nullable|date_format:H:i',
            'toleransi.*' => 'nullable|integer|min:0',
        ]);

        try {
            $jamMasukData = $request->input('jam_masuk', []);
            $jamKeluarData = $request->input('jam_keluar', []);
            $toleransiData = $request->input('toleransi', []);

            foreach ($jamMasukData as $id => $jamMasuk) {
                
                $jadwal = JadwalKerja::find($id);

                if ($jadwal) {
                    // Langsung simpan, tidak perlu konversi
                    $jadwal->jam_masuk = $jamMasukData[$id] ?? null;
                    $jadwal->jam_keluar = $jamKeluarData[$id] ?? null;
                    $jadwal->toleransi = $toleransiData[$id] ?? null;
                    $jadwal->save();
                }
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data jadwal: ' . $e->getMessage());
        }

        // Pesan sukses
        return redirect()->back()->with('success', 'Pengaturan jadwal kerja berhasil diperbarui!');
    }
}