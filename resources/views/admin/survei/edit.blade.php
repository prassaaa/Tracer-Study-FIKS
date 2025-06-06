@extends('layouts.app')

@section('title', 'Edit Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Survei</h1>
        <a href="{{ route('admin.survei.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.survei.update', $survei->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Survei <span class="text-red-600">*</span></label>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Survei <span class="text-red-600">*</span></label>
                <input type="text" name="judul" id="judul" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('judul', $survei->judul) }}" required>
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('deskripsi', $survei->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tanggal_mulai', $survei->tanggal_mulai) }}" required>
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tanggal_selesai', $survei->tanggal_selesai) }}" required>
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="flex items-center">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="aktif" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('aktif', $survei->aktif) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Aktifkan survei</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Survei yang aktif akan ditampilkan kepada alumni untuk diisi.</p>
                @error('aktif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    @endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalSelesaiInput = document.getElementById('tanggal_selesai');
        
        tanggalMulaiInput.addEventListener('change', function() {
            if (tanggalSelesaiInput.value && tanggalSelesaiInput.value < tanggalMulaiInput.value) {
                tanggalSelesaiInput.value = tanggalMulaiInput.value;
            }
            tanggalSelesaiInput.min = tanggalMulaiInput.value;
        });
        
        // Set minimal value for tanggal_selesai
        if (tanggalMulaiInput.value) {
            tanggalSelesaiInput.min = tanggalMulaiInput.value;
        }
    });
</script>
@endpush