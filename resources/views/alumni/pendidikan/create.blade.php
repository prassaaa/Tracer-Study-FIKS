@extends('layouts.app')

@section('title', 'Tambah Pendidikan Lanjut')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Pendidikan Lanjut</h1>
        <a href="{{ route('alumni.pendidikan.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('alumni.pendidikan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="institusi" class="block text-sm font-medium text-gray-700 mb-1">Institusi/Perguruan Tinggi <span class="text-red-600">*</span></label>
                    <input type="text" name="institusi" id="institusi" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('institusi') }}" required>
                    @error('institusi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenjang" class="block text-sm font-medium text-gray-700 mb-1">Jenjang Pendidikan <span class="text-red-600">*</span></label>
                    <select name="jenjang" id="jenjang" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                        <option value="S3" {{ old('jenjang') == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                        <option value="Profesi" {{ old('jenjang') == 'Profesi' ? 'selected' : '' }}>Profesi</option>
                        <option value="Spesialis" {{ old('jenjang') == 'Spesialis' ? 'selected' : '' }}>Spesialis</option>
                        <option value="Lainnya" {{ old('jenjang') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenjang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi <span class="text-red-600">*</span></label>
                    <input type="text" name="program_studi" id="program_studi" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('program_studi') }}" required>
                    @error('program_studi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tahun Masuk <span class="text-red-600">*</span></label>
                    <input type="number" name="tahun_masuk" id="tahun_masuk" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tahun_masuk') }}" required min="1990" max="{{ date('Y') }}">
                    @error('tahun_masuk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tahun_lulus" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" id="tahun_lulus" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tahun_lulus') }}" min="1990" max="{{ date('Y') }}" {{ old('sedang_berjalan') ? 'disabled' : '' }}>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="sedang_berjalan" id="sedang_berjalan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('sedang_berjalan') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Masih dalam proses pendidikan</span>
                        </label>
                    </div>
                    @error('tahun_lulus')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('keterangan') }}</textarea>
                @error('keterangan')
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
        const checkboxSedangBerjalan = document.getElementById('sedang_berjalan');
        const tahunLulusInput = document.getElementById('tahun_lulus');
        
        checkboxSedangBerjalan.addEventListener('change', function() {
            tahunLulusInput.disabled = this.checked;
            if (this.checked) {
                tahunLulusInput.value = '';
            }
        });
    });
</script>
@endpush