<?php

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

        if (!Auth::check()) {
            return redirect('login');
        }

        foreach ($roles as $role) {
            if ($request->user()->role == $role) {
                // Jika cocok, lanjutkan
                return $next($request);
            }
        }

        return redirect('/login')->with('error', 'Anda tidak punya hak akses.');
    }
}