<?php

namespace App\Http\Controllers;

use App\Models\Survei;
use App\Models\PertanyaanSurvei;
use App\Models\JawabanSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveiAlumniController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        
        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }
        
        // Ambil survei yang aktif
        $surveis = Survei::where('aktif', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->get();
        
        // Cek survei mana yang sudah dijawab
        foreach ($surveis as $survei) {
            $pertanyaanIds = $survei->pertanyaanSurvei()->pluck('id');
            $jawabanCount = JawabanSurvei::where('alumni_profile_id', $profile->id)
                ->whereIn('pertanyaan_survei_id', $pertanyaanIds)
                ->count();
            
            $survei->sudah_dijawab = $jawabanCount > 0;
            $survei->total_pertanyaan = $pertanyaanIds->count();
            $survei->pertanyaan_dijawab = $jawabanCount;
        }
        
        return view('alumni.survei.index', compact('surveis'));
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        
        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }
        
        $survei = Survei::with(['pertanyaanSurvei' => function($query) {
            $query->orderBy('urutan', 'asc');
        }])->findOrFail($id);
        
        // Periksa apakah survei aktif
        if (!$survei->aktif || now() < $survei->tanggal_mulai || now() > $survei->tanggal_selesai) {
            return redirect()->route('alumni.survei.index')
                ->with('error', 'Survei tidak tersedia.');
        }
        
        // Ambil jawaban yang sudah ada
        $jawaban = JawabanSurvei::where('alumni_profile_id', $profile->id)
            ->whereIn('pertanyaan_survei_id', $survei->pertanyaanSurvei->pluck('id'))
            ->pluck('jawaban', 'pertanyaan_survei_id');
        
        return view('alumni.survei.show', compact('survei', 'jawaban'));
    }
    
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $profile = $user->alumniProfile;
        
        if (!$profile) {
            return redirect()->route('alumni.profile')
                ->with('error', 'Anda harus melengkapi profil terlebih dahulu.');
        }
        
        $survei = Survei::with('pertanyaanSurvei')->findOrFail($id);
        
        // Periksa apakah survei aktif
        if (!$survei->aktif || now() < $survei->tanggal_mulai || now() > $survei->tanggal_selesai) {
            return redirect()->route('alumni.survei.index')
                ->with('error', 'Survei tidak tersedia.');
        }
        
        // Validasi jawaban
        $rules = [];
        $messages = [];
        
        foreach ($survei->pertanyaanSurvei as $pertanyaan) {
            if ($pertanyaan->wajib) {
                $rules['jawaban.' . $pertanyaan->id] = 'required';
                $messages['jawaban.' . $pertanyaan->id . '.required'] = 'Pertanyaan "' . $pertanyaan->pertanyaan . '" wajib dijawab.';
            }
        }
        
        $request->validate($rules, $messages);
        
        // Simpan jawaban
        foreach ($request->jawaban as $pertanyaanId => $jawaban) {
            // Cek apakah jawaban sudah ada
            $jawabanExisting = JawabanSurvei::where('alumni_profile_id', $profile->id)
                ->where('pertanyaan_survei_id', $pertanyaanId)
                ->first();
            
            if ($jawabanExisting) {
                $jawabanExisting->update(['jawaban' => $jawaban]);
            } else {
                JawabanSurvei::create([
                    'alumni_profile_id' => $profile->id,
                    'pertanyaan_survei_id' => $pertanyaanId,
                    'jawaban' => $jawaban,
                ]);
            }
        }
        
        return redirect()->route('alumni.survei.index')
            ->with('success', 'Terima kasih, jawaban survei Anda telah disimpan.');
    }
}