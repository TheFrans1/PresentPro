<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // <-- Ini sudah benar
use Illuminate\Support\Str;

class IzinController extends Controller
{
    
    public function index()
    {
        
        $userId = Auth::id();

        $riwayatIzin = Izin::where('user_id', $userId)
                            ->orderBy('tanggal_pengajuan', 'desc')
                            ->get();
        
        return view('karyawan.izin.riwayat', compact('riwayatIzin'));
    }

    public function create()
    {
        return view('karyawan.izin.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'jenis' => 'required|in:Izin,Sakit',
                'tanggal_mulai' => 'required|date_format:d/m/Y',
                'tanggal_selesai' => 'required|date_format:d/m/Y|after_or_equal:tanggal_mulai',
                'keterangan' => 'required|string',
                'file_bukti' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            ],
            [
                'jenis.required' => 'Jenis pengajuan wajib dipilih.',
                'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
                'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
                'keterangan.required' => 'Keterangan (alasan) wajib diisi.',
                'file_bukti.required' => 'File bukti wajib di-upload.',
                'file_bukti.mimes' => 'Format file bukti tidak valid. Harap upload file PDF, JPG, atau PNG.',
                'file_bukti.max'   => 'Ukuran file bukti terlalu besar (maksimal 2MB).', 
                'tanggal_selesai.after_or_equal' => 'Tanggal Selesai tidak boleh kurang dari Tanggal Mulai.', 
            ]
        );

        $user = Auth::user();
        $file = $request->file('file_bukti');

        $jenis = strtolower($request->jenis); 

        $namaUserSlug = Str::slug($user->nama, '_'); 

        $extension = $file->getClientOriginalExtension(); // misal: "pdf"

        $fileName = $jenis . '_' . $namaUserSlug . '_' . time() . '.' . $extension;
        
        $file->storeAs('surat_izin', $fileName, 'public');

        Izin::create([
            'user_id' => $user->id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_selesai)->format('Y-m-d'),
            'keterangan' => $request->keterangan,
            'file_bukti' => $fileName, // Nama file sudah benar
            'status_approval' => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('karyawan.dashboard')
                         ->with('success', 'Pengajuan izin/sakit Anda telah berhasil dikirim dan menunggu persetujuan.');
    }
}