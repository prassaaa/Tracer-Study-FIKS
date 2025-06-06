<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlumniApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('role', 'alumni')
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
                            
        return view('admin.alumni-approval.index', compact('pendingUsers'));
    }
    
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        // Log untuk debugging
        Log::info('User data for approval:', ['user_id' => $user->id, 'nim' => $user->nim]);
        
        return view('admin.alumni-approval.approve', compact('user'));
    }
    
    public function storeApprove(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Log data request untuk debugging
        Log::info('Approval request data:', $request->all());
        Log::info('User before approval:', $user->toArray());
        
        // Validasi dasar untuk nama lengkap dan NIM jika diperlukan
        if (empty($user->nim)) {
            $validationRules = [
                'nim' => 'required|string|max:20|unique:alumni_profiles,nim|unique:users,nim,' . $user->id,
                'nama_lengkap' => 'required|string|max:255',
            ];
        } else {
            $validationRules = [
                'nama_lengkap' => 'required|string|max:255',
            ];
        }
        
        $request->validate($validationRules);
        
        // Update user dengan NIM jika belum ada dan ubah status menjadi approved
        if (empty($user->nim) && $request->has('nim')) {
            // Periksa apakah NIM sudah digunakan oleh user lain
            $existingUser = User::where('nim', $request->nim)->where('id', '!=', $user->id)->first();
            if ($existingUser) {
                return back()->withErrors(['nim' => 'NIM ini sudah digunakan oleh pengguna lain.'])->withInput();
            }
            
            $user->update([
                'nim' => $request->nim,
                'status' => 'approved'
            ]);
        } else {
            $user->update([
                'status' => 'approved'
            ]);
        }
        
        // Periksa apakah NIM sudah ada di tabel alumni_profiles
        $nim = $user->nim ?? $request->nim;
        $existingProfile = AlumniProfile::where('nim', $nim)->first();
        if ($existingProfile) {
            return back()->withErrors(['nim' => 'NIM ini sudah digunakan oleh alumni lain.'])->withInput();
        }
        
        try {
            // Buat profil alumni dengan data minimal
            $alumniProfile = new AlumniProfile();
            $alumniProfile->user_id = $user->id;
            $alumniProfile->nim = $nim;
            $alumniProfile->nama_lengkap = $request->nama_lengkap;
            
            // Simpan profil alumni tanpa mengisi field lainnya
            $alumniProfile->save();
            
            // Log data setelah approval untuk debugging
            Log::info('User after approval:', $user->toArray());
            Log::info('Created alumni profile:', $alumniProfile->toArray());
            
            return redirect()->route('admin.alumni-approval.index')
                ->with('success', 'Alumni berhasil disetujui. Alumni dapat melengkapi profil mereka melalui dashboard.');
                
        } catch (\Exception $e) {
            Log::error('Error creating alumni profile: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat profil alumni: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function reject($id)
    {
        $user = User::findOrFail($id);
        
        // Update status user menjadi rejected
        $user->update([
            'status' => 'rejected'
        ]);
        
        return redirect()->route('admin.alumni-approval.index')
            ->with('success', 'Permintaan pendaftaran alumni berhasil ditolak.');
    }
    
    public function approved()
    {
        $approvedUsers = User::where('role', 'alumni')
                             ->where('status', 'approved')
                             ->orderBy('name', 'asc')
                             ->paginate(10);
                             
        return view('admin.alumni-approval.approved', compact('approvedUsers'));
    }
    
    public function rejected()
    {
        $rejectedUsers = User::where('role', 'alumni')
                             ->where('status', 'rejected')
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);
                             
        return view('admin.alumni-approval.rejected', compact('rejectedUsers'));
    }
}