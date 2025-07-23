<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AlumniProfile;
use App\Models\ProgramStudi;
use App\Exports\AlumniExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class AdminController extends Controller
{
    public function index()
    {
        $alumni = AlumniProfile::with(['user', 'programStudi'])->paginate(10);
        return view('admin.alumni.index', compact('alumni'));
    }

    public function create()
    {
        $programStudis = ProgramStudi::all();
        return view('admin.alumni.create', compact('programStudis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nim' => 'required|string|max:20|unique:alumni_profiles',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'alumni',
        ]);

        // Buat profil alumni
        AlumniProfile::create([
            'user_id' => $user->id,
            'program_studi_id' => $request->program_studi_id,
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function show($id)
    {
        $alumni = AlumniProfile::with(['user', 'programStudi', 'riwayatPekerjaan', 'pendidikanLanjut'])
            ->findOrFail($id);
        return view('admin.alumni.show', compact('alumni'));
    }

    public function edit($id)
    {
        $alumni = AlumniProfile::with('user')->findOrFail($id);
        $programStudis = ProgramStudi::all();
        return view('admin.alumni.edit', compact('alumni', 'programStudis'));
    }

    public function update(Request $request, $id)
    {
        $alumni = AlumniProfile::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $alumni->user_id,
            'nim' => 'required|string|max:20|unique:alumni_profiles,nim,' . $id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        // Update user
        $alumni->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $alumni->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update alumni profile
        $alumni->update([
            'program_studi_id' => $request->program_studi_id,
            'nim' => $request->nim,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alumni = AlumniProfile::findOrFail($id);
        $user = $alumni->user;

        // Hapus profil alumni
        $alumni->delete();

        // Hapus user
        $user->delete();

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Data alumni berhasil dihapus.');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function createAlumniProfile($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->alumniProfile) {
            return redirect()->route('admin.alumni.index')
                ->with('info', 'Alumni ini sudah memiliki profil.');
        }

        // Pastikan user sudah disetujui
        if ($user->status !== 'approved') {
            $user->update(['status' => 'approved']);
        }

        $programStudis = ProgramStudi::all();
        return view('admin.alumni.create-profile', compact('user', 'programStudis'));
    }

    public function storeAlumniProfile(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

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

        // Proses upload foto jika ada
        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/alumni', $fotoName);
        }

        // Buat profil alumni
        AlumniProfile::create([
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

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Profil alumni berhasil dibuat.');
    }

    public function exportExcel()
    {
        try {
            $data = AlumniExport::getData();

            if (empty($data)) {
                return redirect()->route('admin.alumni.index')
                    ->with('error', 'Tidak ada data alumni untuk diekspor.');
            }

            $filename = 'data-alumni-' . date('Y-m-d') . '.xlsx';

            // Coba dengan FastExcel
            return (new FastExcel($data))->download($filename);

        } catch (\Exception $e) {
            Log::error('Export Excel Error: ' . $e->getMessage());

            // Fallback ke CSV jika Excel gagal
            return $this->exportCSV();
        }
    }

    public function exportCSV()
    {
        try {
            $data = AlumniExport::getData();

            $filename = 'data-alumni-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                foreach ($data as $row) {
                    fputcsv($file, $row, ';'); // Use semicolon for Excel compatibility
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export CSV Error: ' . $e->getMessage());

            return redirect()->route('admin.alumni.index')
                ->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }
}
