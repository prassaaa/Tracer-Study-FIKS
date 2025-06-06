<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status === 'pending') {
            Auth::logout();
            return redirect()->route('login')->with('status', 'Akun Anda masih menunggu persetujuan admin. Silakan coba lagi nanti.');
        }
        
        if (Auth::check() && Auth::user()->status === 'rejected') {
            Auth::logout();
            return redirect()->route('login')->with('status', 'Pendaftaran Anda telah ditolak. Silakan hubungi administrator untuk informasi lebih lanjut.');
        }
        
        return $next($request);
    }
}