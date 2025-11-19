<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 

use App\Models\Izin;
use App\Models\Absensi;
use App\Models\JadwalKerja;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod; 

class IzinController extends Controller
{
    public function index(Request $request) // <-- Tambahkan Request
    {
       
        $query = Izin::query()
                    ->where('status_approval', 'Pending')
                    ->join('users', 'izin.user_id', '=', 'users.id') // Join untuk filter
                    ->select('izin.*'); // Pilih kolom dari tabel izins

       
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
        

        $izinPending = $query->with('user') 
                        ->orderBy('izin.tanggal_pengajuan', 'desc')
                        ->paginate(10); // <-- Gunakan paginate()
        
        
        $izinPending->appends($request->all());

        return view('admin.izin.index', compact('izinPending')); 
    }


    public function riwayat(Request $request) // <-- Tambahkan Request
    {
         
         $query = Izin::query()
                    ->whereIn('status_approval', ['Disetujui', 'Ditolak'])
                    ->join('users', 'izin.user_id', '=', 'users.id') 
                    ->select('izin.*'); 

       
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

         
         $izinRiwayat = $query->with('user')
                        ->orderBy('izin.tanggal_pengajuan', 'desc')
                        ->paginate(10); // <-- Gunakan paginate()

        
        $izinRiwayat->appends($request->all());
                        
        return view('admin.izin.riwayat', compact('izinRiwayat')); 
    }

    public function setujui(Izin $izin)
    {
        
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];

        DB::beginTransaction();
        try {
           
            $izin->update(['status_approval' => 'Disetujui']);

           
            $period = CarbonPeriod::create($izin->tanggal_mulai, $izin->tanggal_selesai);

            
            foreach ($period as $date) {
                
               
                $namaHariInggris = $date->format('l');
                $namaHariIni = $dayMap[$namaHariInggris];
                $jadwalHariIni = JadwalKerja::where('hari', $namaHariIni)->first();

               
                if ($jadwalHariIni && $jadwalHariIni->jam_masuk != null && $jadwalHariIni->jam_masuk != '00:00:00') {
                    
                    
                    
                    Absensi::updateOrCreate(
                        [
                            'user_id' => $izin->user_id,
                            'tanggal' => $date->format('Y-m-d')
                        ],
                        [
                            'status_absensi' => $izin->jenis,
                            'ket_status_msk' => $izin->keterangan, 
                            'jam_masuk' => null,
                            'jam_keluar' => null,
                            'foto_masuk' => null,
                            'foto_pulang' => null,
                            'durasi_bekerja' => null, 
                            'status_pulang' => null
                        ]
                    );
                }
            }
            
         
            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan izin berhasil disetujui dan dicatat di rekap absensi.');

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui izin: ' . $e->getMessage());
        }
    }


    public function tolak(Izin $izin)
    {
        
        $izin->update(['status_approval' => 'Ditolak']);
        
      
        Absensi::where('user_id', $izin->user_id)
               ->where('tanggal', '>=', $izin->tanggal_mulai)
               ->where('tanggal', '<=', $izin->tanggal_selesai)
               ->whereIn('status_absensi', ['Izin', 'Sakit']) 
               ->delete();

        return redirect()->back()->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}