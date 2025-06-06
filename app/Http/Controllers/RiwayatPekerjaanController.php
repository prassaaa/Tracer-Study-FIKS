<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPekerjaan;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPekerjaanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;

        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }

        $riwayatPekerjaan = $profile->riwayatPekerjaan()->orderBy('tanggal_mulai', 'desc')->get();

        return view('alumni.riwayat-pekerjaan.index', compact('riwayatPekerjaan'));
    }

    public function create()
    {
        return view('alumni.riwayat-pekerjaan.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;

        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'bidang_pekerjaan' => 'required|string|max:255',
            'gaji' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi_pekerjaan' => 'nullable|string',
        ]);

        // Membuat data untuk disimpan
        $data = [
            'nama_perusahaan' => $request->nama_perusahaan,
            'posisi' => $request->posisi,
            'lokasi' => $request->lokasi,
            'bidang_pekerjaan' => $request->bidang_pekerjaan,
            'gaji' => $request->gaji,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            // Explicitly set pekerjaan_saat_ini to 1 or 0
            'pekerjaan_saat_ini' => $request->has('pekerjaan_saat_ini') ? 1 : 0,
        ];

        // Jika pekerjaan saat ini
        if ($data['pekerjaan_saat_ini'] == 1) {
            $profile->riwayatPekerjaan()->update(['pekerjaan_saat_ini' => 0]);
        }

        try {
            $profile->riwayatPekerjaan()->create($data);
            return redirect()->route('alumni.riwayat-pekerjaan.index')
                ->with('success', 'Riwayat pekerjaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error saving work history: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $riwayatPekerjaan = RiwayatPekerjaan::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        return view('alumni.riwayat-pekerjaan.show', compact('riwayatPekerjaan'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $riwayatPekerjaan = RiwayatPekerjaan::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        return view('alumni.riwayat-pekerjaan.edit', compact('riwayatPekerjaan'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $riwayatPekerjaan = RiwayatPekerjaan::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'bidang_pekerjaan' => 'required|string|max:255',
            'gaji' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'pekerjaan_saat_ini' => 'nullable|boolean',
            'deskripsi_pekerjaan' => 'nullable|string',
        ]);

        // Jika pekerjaan saat ini dicentang, set semua pekerjaan lain bukan pekerjaan saat ini
        if ($request->pekerjaan_saat_ini) {
            $profile->riwayatPekerjaan()->where('id', '!=', $id)->update(['pekerjaan_saat_ini' => false]);
        }

        $riwayatPekerjaan->update($request->all());

        return redirect()->route('alumni.riwayat-pekerjaan.index')
            ->with('success', 'Riwayat pekerjaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $riwayatPekerjaan = RiwayatPekerjaan::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        $riwayatPekerjaan->delete();

        return redirect()->route('alumni.riwayat-pekerjaan.index')
            ->with('success', 'Riwayat pekerjaan berhasil dihapus.');
    }
}
