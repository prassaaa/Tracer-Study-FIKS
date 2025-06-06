<?php

namespace App\Http\Controllers;

use App\Models\AlumniProfile;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $programStudis = ProgramStudi::all();
        
        return view('alumni.profile', compact('profile', 'programStudis'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Cek status persetujuan
        if ($user->status !== 'approved') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak dapat mengedit profil karena akun Anda belum disetujui.');
        }
        
        // Cek apakah user sudah memiliki profil
        if (!$user->alumniProfile) {
            return redirect()->route('alumni.profile.create')
                ->with('info', 'Profil Anda belum tersedia. Silakan buat profil terlebih dahulu.');
        }
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profile = $user->alumniProfile;
        
        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($profile->foto && Storage::exists('public/alumni/' . $profile->foto)) {
                Storage::delete('public/alumni/' . $profile->foto);
            }
            
            // Upload foto baru
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/alumni', $fotoName);
            
            $profile->foto = $fotoName;
        }
        
        // Update profil
        $profile->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'program_studi_id' => $request->program_studi_id,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'foto' => $profile->foto,
        ]);
        
        return redirect()->route('alumni.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function createProfile()
    {
        $user = Auth::user();
        
        // Jika user sudah memiliki profil, redirect ke halaman edit profil
        if ($user->alumniProfile) {
            return redirect()->route('alumni.profile')
                ->with('info', 'Anda sudah memiliki profil alumni.');
        }
        
        // Jika user belum disetujui, redirect ke dashboard
        if ($user->status !== 'approved') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak dapat membuat profil karena akun Anda belum disetujui.');
        }
        
        $programStudis = ProgramStudi::all();
        return view('alumni.create-profile', compact('programStudis'));
    }

    public function storeProfile(Request $request)
    {
        $user = Auth::user();
        
        // Validasi data
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Proses upload foto jika ada
        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/alumni', $fotoName);
        }
        
        // Buat profil alumni
        try {
            $profile = new AlumniProfile([
                'user_id' => $user->id,
                'program_studi_id' => $request->program_studi_id,
                'nim' => $user->nim,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tahun_masuk' => $request->tahun_masuk,
                'tahun_lulus' => $request->tahun_lulus,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'foto' => $fotoName,
            ]);
            
            $profile->save();
            
            // Refresh user data
            $user->refresh();
            
            // Simpan dalam session bahwa profil telah dibuat
            session(['profile_created' => true]);
            
            return redirect()->route('alumni.profile')
                ->with('success', 'Profil berhasil dibuat. Silakan refresh halaman jika profil tidak terlihat.');
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error creating profile', ['message' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}