<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- 1. TAMBAHKAN IMPORT INI
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    // Variabel untuk menyimpan daftar Jabatan (ENUM)
    private $jabatanOptions = [
        'Divisi IT', 
        'Keuangan', 
        'HRD', 
        'Pemasaran', 
        'Operasional', 
        'Administrator'
    ];

    /**
     * Menampilkan halaman daftar karyawan (Read).
     * (SUDAH DITAMBAHKAN FUNGSI SEARCH)
     */
    public function index(Request $request) // <-- 2. TAMBAHKAN 'Request $request'
    {
        // 3. Mulai query dasar
        $query = User::where('role', 'karyawan');

        // =============================================
        // == 4. INI ADALAH LOGIKA PENCARIAN BARU ANDA ==
        // =============================================
        // Jika ada input 'filter_nik' (dari form Anda)
        if ($request->filled('filter_nik')) {
            // Lanjutkan query dengan filter NIK
            $query->where('nik', 'like', '%' . $request->filter_nik . '%');
        }
        
        // 5. Ambil semua data (setelah difilter)
        $users = $query->orderBy('nama', 'asc')->get();
        
        return view('admin.akun.index', compact('users'));
    }

    /**
     * Menampilkan form untuk menambah karyawan baru (Create).
     */
    public function create()
    {
        // Kirim daftar jabatan ke view
        return view('admin.akun.create', [
            'jabatanOptions' => $this->jabatanOptions
        ]);
    }

    /**
     * Menyimpan karyawan baru ke database (Create).
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'nik' => 'required|string|max:4|unique:users', 
            'jabatan' => ['required', Rule::in($this->jabatanOptions)], // Validasi ENUM
            'no_hp' => 'nullable|numeric|digits_between:11,12',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        // 2. Buat user baru
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'username' => $request->nik, // Gunakan NIK sebagai USERNAME LOGIN
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
            'status' => 'aktif',
        ]);

        // 3. Redirect
        return redirect()->route('admin.akun.index')
                         ->with('success', 'Akun karyawan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit akun karyawan.
     */
    public function edit(User $user)
    {
        // Kirim data user dan daftar jabatan ke view
        return view('admin.akun.edit', [
            'user' => $user,
            'jabatanOptions' => $this->jabatanOptions
        ]);
    }
    
    /**
     * Mengupdate akun karyawan.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nik' => [
                'required', 'string', 'max:4',
                Rule::unique('users')->ignore($user->id),
            ],
            'jabatan' => ['required', Rule::in($this->jabatanOptions)], // Validasi ENUM
            'no_hp' => 'nullable|numeric|digits_between:11,12',
            'alamat' => 'nullable|string',
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'username' => $request->nik, // Update username login juga
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.akun.index')
                         ->with('success', "Data akun '{$user->nama}' berhasil di-update.");
    }
    
    /**
     * Mereset password karyawan.
     */
    public function resetPassword(User $user)
    {
        $newPassword = '12345678';
        $user->update(['password' => Hash::make($newPassword)]);
        return redirect()->route('admin.akun.index')
                         ->with('success', "Password untuk '{$user->nama}' telah di-reset menjadi '{$newPassword}'.");
    }

    /**
     * Mengubah status (toggle) aktif/nonaktif karyawan.
     */
    public function toggleStatus(User $user)
    {
        $newStatus = ($user->status == 'aktif') ? 'nonaktif' : 'aktif';
        $message = ($newStatus == 'nonaktif')
                    ? "Akun '{$user->nama}' telah dinonaktifkan."
                    : "Akun '{$user->nama}' telah diaktifkan kembali.";
        $user->update(['status' => $newStatus]);
        return redirect()->route('admin.akun.index')->with('success', $message);
    }
}