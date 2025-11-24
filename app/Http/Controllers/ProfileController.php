<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // List divisi yang dipakai di form
        $divisiList = [
            'Divisi IT',
            'Keuangan',
            'HRD',
            'Pemasaran',
            'Operasional',
            'Administrator'
        ];

        // Admin pakai view admin
        if ($user->role === 'admin') {
            return view('profile.admin', compact('user', 'divisiList'));
        }

        // Karyawan pakai view karyawan
        return view('profile.karyawan', compact('user', 'divisiList'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update field lain
        $user->nama = $request->nama;
        $user->jabatan = $request->jabatan;
        $user->no_hp = $request->no_hp;

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
