<?php

namespace App\Http\Controllers;

use App\Models\Survei;
use App\Models\PertanyaanSurvei;
use Illuminate\Http\Request;

class SurveiController extends Controller
{
    public function index()
    {
        $surveis = Survei::withCount('pertanyaanSurvei')->paginate(10);
        return view('admin.survei.index', compact('surveis'));
    }

    public function create()
    {
        return view('admin.survei.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            // Hapus validasi boolean untuk aktif
        ]);

        // Buat array data dari request
        $data = $request->except('aktif');

        // Tetapkan nilai checkbox secara manual
        $data['aktif'] = $request->has('aktif') ? 1 : 0;

        try {
            // Debug log
            \Log::info('Survei data to save:', $data);

            $survei = Survei::create($data);

            // Debug log success
            \Log::info('Survei created successfully:', ['id' => $survei->id ?? 'null']);

            return redirect()->route('admin.survei.index')
                ->with('success', 'Survei berhasil dibuat.');
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error saving survey: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $survei = Survei::with('pertanyaanSurvei')->findOrFail($id);
        return view('admin.survei.show', compact('survei'));
    }

    public function edit($id)
    {
        $survei = Survei::findOrFail($id);
        return view('admin.survei.edit', compact('survei'));
    }

    public function update(Request $request, $id)
    {
        $survei = Survei::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            // Hapus validasi boolean untuk aktif
        ]);

        // Buat array data dari request
        $data = $request->except(['aktif', '_token', '_method']);

        // Tetapkan nilai checkbox secara manual
        $data['aktif'] = $request->has('aktif') ? 1 : 0;

        try {
            // Debug log
            \Log::info('Survei data to update:', $data);

            $survei->update($data);

            // Debug log success
            \Log::info('Survei updated successfully');

            return redirect()->route('admin.survei.index')
                ->with('success', 'Survei berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error updating survey: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $survei = Survei::findOrFail($id);
        $survei->delete();

        return redirect()->route('admin.survei.index')
            ->with('success', 'Survei berhasil dihapus.');
    }

    // Mengelola pertanyaan survei
    public function pertanyaan($id)
    {
        $survei = Survei::with('pertanyaanSurvei')->findOrFail($id);
        return view('admin.survei.pertanyaan.index', compact('survei'));
    }

    public function createPertanyaan($id)
    {
        $survei = Survei::findOrFail($id);
        return view('admin.survei.pertanyaan.create', compact('survei'));
    }

    public function storePertanyaan(Request $request, $id)
    {
        $survei = Survei::findOrFail($id);

        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|string|in:text,radio,checkbox,select',
            'pilihan' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            // Hapus validasi boolean untuk wajib
        ]);

        // Konversi pilihan menjadi array jika tipe bukan text
        $pilihan = null;
        if ($request->tipe != 'text' && $request->filled('pilihan')) {
            $pilihan = explode("\n", $request->pilihan);
            $pilihan = array_map('trim', $pilihan);
            $pilihan = array_filter($pilihan);
        }

        try {
            $survei->pertanyaanSurvei()->create([
                'pertanyaan' => $request->pertanyaan,
                'tipe' => $request->tipe,
                'pilihan' => $pilihan,
                'urutan' => $request->urutan,
                'wajib' => $request->has('wajib') ? 1 : 0,
            ]);

            return redirect()->route('admin.survei.pertanyaan', $survei->id)
                ->with('success', 'Pertanyaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error adding question: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editPertanyaan($id, $pertanyaanId)
    {
        $survei = Survei::findOrFail($id);
        $pertanyaan = PertanyaanSurvei::where('survei_id', $id)->findOrFail($pertanyaanId);

        return view('admin.survei.pertanyaan.edit', compact('survei', 'pertanyaan'));
    }

    public function updatePertanyaan(Request $request, $id, $pertanyaanId)
    {
        $survei = Survei::findOrFail($id);
        $pertanyaan = PertanyaanSurvei::where('survei_id', $id)->findOrFail($pertanyaanId);

        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|string|in:text,radio,checkbox,select',
            'pilihan' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            // Hapus validasi boolean untuk wajib
        ]);

        // Konversi pilihan menjadi array jika tipe bukan text
        $pilihan = null;
        if ($request->tipe != 'text' && $request->filled('pilihan')) {
            $pilihan = explode("\n", $request->pilihan);
            $pilihan = array_map('trim', $pilihan);
            $pilihan = array_filter($pilihan);
        }

        try {
            $pertanyaan->update([
                'pertanyaan' => $request->pertanyaan,
                'tipe' => $request->tipe,
                'pilihan' => $pilihan,
                'urutan' => $request->urutan,
                'wajib' => $request->has('wajib') ? 1 : 0,
            ]);

            return redirect()->route('admin.survei.pertanyaan', $survei->id)
                ->with('success', 'Pertanyaan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating question: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroyPertanyaan($id, $pertanyaanId)
    {
        $pertanyaan = PertanyaanSurvei::where('survei_id', $id)->findOrFail($pertanyaanId);
        $pertanyaan->delete();

        return redirect()->route('admin.survei.pertanyaan', $id)
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
