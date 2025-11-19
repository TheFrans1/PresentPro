<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
 
    private $jabatanOptions = [
        'Divisi IT', 
        'Keuangan', 
        'HRD', 
        'Pemasaran', 
        'Operasional', 
        'Administrator'
    ];

    public function index(Request $request) 
    {
       
        $query = User::where('role', 'karyawan');

       
        if ($request->filled('filter_nik')) {
           
            $query->where('nik', 'like', '%' . $request->filter_nik . '%');
        }
        
       
        $users = $query->orderBy('nama', 'asc')->get();
        
        return view('admin.akun.index', compact('users'));
    }

   
    public function create()
    {
        // Kirim daftar jabatan ke view
        return view('admin.akun.create', [
            'jabatanOptions' => $this->jabatanOptions
        ]);
    }

    
    public function store(Request $request)
    {
       
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'nik' => 'required|string|max:4|unique:users', 
            'jabatan' => ['required', Rule::in($this->jabatanOptions)], // Validasi ENUM
            'no_hp' => 'nullable|numeric|digits_between:11,12',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'username' => $request->nik, 
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
            'status' => 'aktif',
        ]);

      
        return redirect()->route('admin.akun.index')
                         ->with('success', 'Akun karyawan baru berhasil ditambahkan.');
    }

   
    public function edit(User $user)
    {
      
        return view('admin.akun.edit', [
            'user' => $user,
            'jabatanOptions' => $this->jabatanOptions
        ]);
    }
    
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
            'jabatan' => ['required', Rule::in($this->jabatanOptions)], 
            'no_hp' => 'nullable|numeric|digits_between:11,12',
            'alamat' => 'nullable|string',
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'username' => $request->nik, 
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.akun.index')
                         ->with('success', "Data akun '{$user->nama}' berhasil di-update.");
    }
    

    public function resetPassword(User $user)
    {
        $newPassword = '12345678';
        $user->update(['password' => Hash::make($newPassword)]);
        return redirect()->route('admin.akun.index')
                         ->with('success', "Password untuk '{$user->nama}' telah di-reset menjadi '{$newPassword}'.");
    }

    
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