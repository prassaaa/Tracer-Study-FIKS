@extends('layouts.app')

@section('title', 'Tambah Pertanyaan Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Pertanyaan</h1>
            <p class="text-gray-600">Survei: {{ $survei->judul }}</p>
        </div>
        <a href="{{ route('admin.survei.pertanyaan', $survei->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.survei.pertanyaan.store', $survei->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-600">*</span></label>
                <input type="text" name="pertanyaan" id="pertanyaan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('pertanyaan') }}" required>
                @error('pertanyaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="mb-4">
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pertanyaan <span class="text-red-600">*</span></label>
                    <select name="tipe" id="tipe" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="text" {{ old('tipe') == 'text' ? 'selected' : '' }}>Text (Isian)</option>
                        <option value="radio" {{ old('tipe') == 'radio' ? 'selected' : '' }}>Radio (Pilihan Tunggal)</option>
                        <option value="checkbox" {{ old('tipe') == 'checkbox' ? 'selected' : '' }}>Checkbox (Pilihan Ganda)</option>
                        <option value="select" {{ old('tipe') == 'select' ? 'selected' : '' }}>Select (Dropdown)</option>
                    </select>
                    @error('tipe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="urutan" class="block text-sm font-medium text-gray-700 mb-1">Urutan <span class="text-red-600">*</span></label>
                    <input type="number" name="urutan" id="urutan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('urutan', $survei->pertanyaanSurvei->count() + 1) }}" min="1" required>
                    @error('urutan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Wajib Diisi?</label>
                    <div class="flex items-center">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="wajib" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('wajib') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Ya, pertanyaan ini wajib dijawab</span>
                        </label>
                    </div>
                    @error('wajib')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4 pilihan-section" style="display: none;">
                <label for="pilihan" class="block text-sm font-medium text-gray-700 mb-1">Pilihan Jawaban <span class="text-red-600">*</span></label>
                <textarea name="pilihan" id="pilihan" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Masukkan setiap pilihan pada baris baru.&#10;Contoh:&#10;Sangat Setuju&#10;Setuju&#10;Netral&#10;Tidak Setuju&#10;Sangat Tidak Setuju">{{ old('pilihan') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Masukkan setiap pilihan pada baris baru.</p>
                @error('pilihan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSelect = document.getElementById('tipe');
        const pilihanSection = document.querySelector('.pilihan-section');
        const pilihanTextarea = document.getElementById('pilihan');
        
        function togglePilihanSection() {
            if (tipeSelect.value === 'text') {
                pilihanSection.style.display = 'none';
                pilihanTextarea.removeAttribute('required');
            } else {
                pilihanSection.style.display = 'block';
                pilihanTextarea.setAttribute('required', 'required');
            }
        }
        
        tipeSelect.addEventListener('change', togglePilihanSection);
        
        // Initialize state based on initial value
        togglePilihanSection();
    });
</script>
@endpush