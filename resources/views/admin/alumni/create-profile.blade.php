@extends('layouts.app')

@section('title', 'Buat Profil Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buat Profil Alumni</h1>
        <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Akun</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p><strong>Nama:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>NIM:</strong> {{ $user->nim }}</p>
                        <p><strong>Tanggal Pendaftaran:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.alumni.store-profile', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Pribadi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nama_lengkap', $user->name) }}" required>
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-600">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="program_studi_id" class="block text-sm font-medium text-gray-700 mb-1">Program Studi <span class="text-red-600">*</span></label>
                    <select name="program_studi_id" id="program_studi_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Program Studi</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                    @error('program_studi_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tahun Masuk <span class="text-red-600">*</span></label>
                    <select name="tahun_masuk" id="tahun_masuk" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Tahun Masuk</option>
                        @foreach(range(date('Y'), 2000) as $year)
                            <option value="{{ $year }}" {{ old('tahun_masuk') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('tahun_masuk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tahun_lulus" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus <span class="text-red-600">*</span></label>
                    <select name="tahun_lulus" id="tahun_lulus" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Tahun Lulus</option>
                        @foreach(range(date('Y'), 2000) as $year)
                            <option value="{{ $year }}" {{ old('tahun_lulus') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('tahun_lulus')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('no_telepon') }}">
                    @error('no_telepon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Simpan Profil
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tahunMasukSelect = document.getElementById('tahun_masuk');
        const tahunLulusSelect = document.getElementById('tahun_lulus');
        
        tahunMasukSelect.addEventListener('change', function() {
            const tahunMasuk = parseInt(this.value);
            
            if (tahunMasuk) {
                // Filter tahun lulus harus >= tahun masuk
                const options = tahunLulusSelect.options;
                
                for (let i = 0; i < options.length; i++) {
                    const tahunLulus = parseInt(options[i].value);
                    
                    if (tahunLulus && tahunLulus < tahunMasuk) {
                        options[i].disabled = true;
                    } else {
                        options[i].disabled = false;
                    }
                }
                
                // Jika tahun lulus yang dipilih sebelumnya tidak valid, reset pilihan
                if (parseInt(tahunLulusSelect.value) < tahunMasuk) {
                    tahunLulusSelect.value = '';
                }
            }
        });
        
        // Initialize on page load
        if (tahunMasukSelect.value) {
            tahunMasukSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush