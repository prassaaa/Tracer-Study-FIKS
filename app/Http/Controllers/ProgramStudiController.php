<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $programStudis = ProgramStudi::withCount('alumniProfiles')->paginate(10);
        return view('admin.program-studi.index', compact('programStudis'));
    }

    public function create()
    {
        return view('admin.program-studi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:program_studis',
            'deskripsi' => 'nullable|string',
        ]);

        ProgramStudi::create($request->all());

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $programStudi = ProgramStudi::with('alumniProfiles')->findOrFail($id);
        return view('admin.program-studi.show', compact('programStudi'));
    }

    public function edit($id)
    {
        $programStudi = ProgramStudi::findOrFail($id);
        return view('admin.program-studi.edit', compact('programStudi'));
    }

    public function update(Request $request, $id)
    {
        $programStudi = ProgramStudi::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:program_studis,kode,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $programStudi->update($request->all());

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $programStudi = ProgramStudi::findOrFail($id);
        
        // Cek apakah program studi memiliki alumni
        if ($programStudi->alumniProfiles()->count() > 0) {
            return redirect()->route('admin.program-studi.index')
                ->with('error', 'Program studi tidak dapat dihapus karena masih memiliki data alumni.');
        }
        
        $programStudi->delete();

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}