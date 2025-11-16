<?php
// File: app/Http/Middleware/CekRole.php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  ...$roles (Contoh: 'admin', 'karyawan')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jika tidak login, lempar ke login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Cek apakah role user ada di daftar $roles yang diizinkan
        foreach ($roles as $role) {
            if ($request->user()->role == $role) {
                // Jika cocok, lanjutkan
                return $next($request);
            }
        }

        // Jika tidak ada role yang cocok, lempar
        // (Mungkin ke halaman 403 atau kembali ke dashboard)
        return redirect('/login')->with('error', 'Anda tidak punya hak akses.');
    }
}