@extends('layouts.app')

@section('title', 'Tambah Riwayat Pekerjaan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Riwayat Pekerjaan</h1>
        <a href="{{ route('alumni.riwayat-pekerjaan.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('alumni.riwayat-pekerjaan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nama_perusahaan') }}" required>
                    @error('nama_perusahaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="posisi" class="block text-sm font-medium text-gray-700 mb-1">Posisi/Jabatan <span class="text-red-600">*</span></label>
                    <input type="text" name="posisi" id="posisi" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('posisi') }}" required>
                    @error('posisi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="bidang_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Bidang Pekerjaan <span class="text-red-600">*</span></label>
                    <select name="bidang_pekerjaan" id="bidang_pekerjaan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Bidang Pekerjaan</option>
                        <option value="Pendidikan" {{ old('bidang_pekerjaan') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="Kesehatan" {{ old('bidang_pekerjaan') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                        <option value="Teknologi Informasi" {{ old('bidang_pekerjaan') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                        <option value="Perbankan" {{ old('bidang_pekerjaan') == 'Perbankan' ? 'selected' : '' }}>Perbankan</option>
                        <option value="Pemerintahan" {{ old('bidang_pekerjaan') == 'Pemerintahan' ? 'selected' : '' }}>Pemerintahan</option>
                        <option value="Swasta" {{ old('bidang_pekerjaan') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                        <option value="BUMN" {{ old('bidang_pekerjaan') == 'BUMN' ? 'selected' : '' }}>BUMN</option>
                        <option value="Wiraswasta" {{ old('bidang_pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                        <option value="Lainnya" {{ old('bidang_pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('bidang_pekerjaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('lokasi') }}">
                    @error('lokasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tanggal_mulai') }}" required>
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tanggal_selesai') }}" {{ old('pekerjaan_saat_ini') ? 'disabled' : '' }}>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="pekerjaan_saat_ini" id="pekerjaan_saat_ini" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('pekerjaan_saat_ini') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Ini adalah pekerjaan saat ini</span>
                        </label>
                    </div>
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gaji" class="block text-sm font-medium text-gray-700 mb-1">Gaji (per bulan, Rp)</label>
                    <input type="number" name="gaji" id="gaji" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('gaji') }}" placeholder="Contoh: 5000000">
                    <p class="text-xs text-gray-500 mt-1">Opsional, data ini akan membantu analisis karir alumni</p>
                    @error('gaji')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="deskripsi_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pekerjaan</label>
                <textarea name="deskripsi_pekerjaan" id="deskripsi_pekerjaan" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Deskripsikan tugas dan tanggung jawab pekerjaan Anda">{{ old('deskripsi_pekerjaan') }}</textarea>
                @error('deskripsi_pekerjaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxPekerjaanSaatIni = document.getElementById('pekerjaan_saat_ini');
        const tanggalSelesaiInput = document.getElementById('tanggal_selesai');

        checkboxPekerjaanSaatIni.addEventListener('change', function() {
            tanggalSelesaiInput.disabled = this.checked;
            if (this.checked) {
                tanggalSelesaiInput.value = '';
            }
        });
    });
</script>
@endpush
