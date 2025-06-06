@extends('layouts.app')

@section('title', 'Proses Clustering Baru')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Proses Clustering Baru</h1>
        <a href="{{ route('admin.clustering.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.clustering.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="nama_proses" class="block text-sm font-medium text-gray-700 mb-1">Nama Proses <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_proses" id="nama_proses" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nama_proses') }}" required>
                    @error('nama_proses')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jumlah_cluster" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Cluster <span class="text-red-600">*</span></label>
                    <input type="number" name="jumlah_cluster" id="jumlah_cluster" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('jumlah_cluster', 3) }}" min="2" max="10" required>
                    <p class="text-xs text-gray-500 mt-1">Jumlah cluster yang akan dibentuk (2-10)</p>
                    @error('jumlah_cluster')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="program_studi_id" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                    <select name="program_studi_id" id="program_studi_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Program Studi</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk memproses semua program studi</p>
                    @error('program_studi_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="tahun_lulus_awal" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus Awal</label>
                        <input type="number" name="tahun_lulus_awal" id="tahun_lulus_awal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tahun_lulus_awal') }}" min="2000" max="{{ date('Y') }}">
                        @error('tahun_lulus_awal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tahun_lulus_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus Akhir</label>
                        <input type="number" name="tahun_lulus_akhir" id="tahun_lulus_akhir" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tahun_lulus_akhir') }}" min="2000" max="{{ date('Y') }}">
                        @error('tahun_lulus_akhir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4 mt-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Atribut yang Digunakan <span class="text-red-600">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="atribut[]" id="atribut_gaji" value="gaji" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array('gaji', old('atribut', [])) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="atribut_gaji" class="font-medium text-gray-700">Gaji</label>
                            <p class="text-gray-500">Gaji alumni pada pekerjaan saat ini</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="atribut[]" id="atribut_bidang_pekerjaan" value="bidang_pekerjaan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array('bidang_pekerjaan', old('atribut', [])) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="atribut_bidang_pekerjaan" class="font-medium text-gray-700">Bidang Pekerjaan</label>
                            <p class="text-gray-500">Bidang pekerjaan alumni</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="atribut[]" id="atribut_waktu_tunggu" value="waktu_tunggu" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array('waktu_tunggu', old('atribut', [])) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="atribut_waktu_tunggu" class="font-medium text-gray-700">Waktu Tunggu</label>
                            <p class="text-gray-500">Waktu tunggu mendapatkan pekerjaan pertama</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="atribut[]" id="atribut_jenjang_pendidikan" value="jenjang_pendidikan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array('jenjang_pendidikan', old('atribut', [])) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="atribut_jenjang_pendidikan" class="font-medium text-gray-700">Jenjang Pendidikan</label>
                            <p class="text-gray-500">Jenjang pendidikan lanjut alumni</p>
                        </div>
                    </div>
                </div>
                @error('atribut')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('atribut.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Algoritma</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Algoritma Single Linkage Clustering adalah metode hierarchical clustering yang menggabungkan cluster berdasarkan jarak minimum antara anggota dari masing-masing cluster.</p>
                            <p class="mt-1">Pilih atribut yang relevan dan jumlah cluster yang sesuai untuk mendapatkan hasil yang optimal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-cogs mr-2"></i> Proses Clustering
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const atributCheckboxes = document.querySelectorAll('input[name="atribut[]"]');
        const submitButton = document.querySelector('button[type="submit"]');
        
        function validateForm() {
            let atLeastOneChecked = false;
            atributCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    atLeastOneChecked = true;
                }
            });
            
            if (!atLeastOneChecked) {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
        
        atributCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', validateForm);
        });
        
        validateForm();
    });
</script>
@endpush