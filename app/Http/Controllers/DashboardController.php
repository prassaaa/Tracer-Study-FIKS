<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AlumniProfile;
use App\Models\RiwayatPekerjaan;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Data untuk dashboard admin
            $totalAlumni = AlumniProfile::count();
            $totalUser = User::where('role', 'alumni')->count();
            $totalProdi = ProgramStudi::count();
            $riwayatPekerjaan = RiwayatPekerjaan::count();
            
            $alumniPerProdi = ProgramStudi::withCount('alumniProfiles')->get();
            
            return view('dashboard', compact(
                'totalAlumni',
                'totalUser',
                'totalProdi',
                'riwayatPekerjaan',
                'alumniPerProdi'
            ));
        } else {
            // Data untuk dashboard alumni
            $profile = $user->alumniProfile;
            $pekerjaan = $profile ? $profile->riwayatPekerjaan()->orderBy('tanggal_mulai', 'desc')->get() : null;
            
            return view('dashboard', compact('profile', 'pekerjaan'));
        }
    }
}