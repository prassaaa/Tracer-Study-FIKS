<?php

namespace App\Http\Controllers;

use App\Models\PendidikanLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendidikanLanjutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;

        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }

        $pendidikanLanjut = $profile->pendidikanLanjut()->orderBy('tahun_masuk', 'desc')->get();

        return view('alumni.pendidikan.index', compact('pendidikanLanjut'));
    }

    public function create()
    {
        return view('alumni.pendidikan.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;

        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }

        // Validasi tanpa boolean
        $request->validate([
            'institusi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'nullable|digits:4',
            'keterangan' => 'nullable|string',
        ]);

        // Siapkan data untuk disimpan
        $data = $request->except('sedang_berjalan', '_token');

        // Explicitly set sedang_berjalan to 1 or 0
        $data['sedang_berjalan'] = $request->has('sedang_berjalan') ? 1 : 0;

        // Jika sedang berjalan, tahun lulus tidak wajib
        if ($data['sedang_berjalan']) {
            $data['tahun_lulus'] = null;
        }

        try {
            // Debug log
            \Log::info('Pendidikan data to save:', $data);

            $result = $profile->pendidikanLanjut()->create($data);

            // Debug log success
            \Log::info('Pendidikan saved successfully:', ['id' => $result->id ?? 'null']);

            return redirect()->route('alumni.pendidikan.index')
                ->with('success', 'Pendidikan lanjut berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error saving education: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $pendidikan = PendidikanLanjut::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        return view('alumni.pendidikan.show', compact('pendidikan'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $pendidikan = PendidikanLanjut::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        return view('alumni.pendidikan.edit', compact('pendidikan'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        $pendidikan = PendidikanLanjut::where('alumni_profile_id', $profile->id)
            ->findOrFail($id);

        // Validasi tanpa boolean
        $request->validate([
            'institusi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'nullable|digits:4',
            'keterangan' => 'nullable|string',
        ]);

        // Siapkan data untuk update
        $data = $request->except('sedang_berjalan', '_token', '_method');

        // Explicitly set sedang_berjalan to 1 or 0
        $data['sedang_berjalan'] = $request->has('sedang_berjalan') ? 1 : 0;

        // Jika sedang berjalan, tahun lulus tidak wajib
        if ($data['sedang_berjalan']) {
            $data['tahun_lulus'] = null;
        }

        try {
            // Debug log
            \Log::info('Pendidikan data to update:', $data);

            $pendidikan->update($data);

            // Debug log success
            \Log::info('Pendidikan updated successfully');

            return redirect()->route('alumni.pendidikan.index')
                ->with('success', 'Pendidikan lanjut berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error updating education: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
