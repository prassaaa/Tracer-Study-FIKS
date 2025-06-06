@extends('layouts.app')

@section('title', 'Buat Profil Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buat Profil Alumni</h1>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md">
            <p class="font-medium">Terdapat kesalahan pada formulir:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('alumni.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="flex flex-col md:flex-row mb-6">
                <div class="w-full md:w-1/3 mb-6 md:mb-0">
                    <div class="text-center">
                        <div class="w-40 h-40 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4" id="foto-preview">
                            <i class="fas fa-user text-blue-500 text-5xl"></i>
                        </div>
                        <label for="foto" class="cursor-pointer text-blue-600 hover:text-blue-800">
                            <span class="px-4 py-2 bg-blue-50 rounded-md"><i class="fas fa-camera mr-2"></i> Pilih Foto</span>
                            <input type="file" name="foto" id="foto" class="hidden" accept="image/*">
                        </label>
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG (Maks. 2MB)</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="w-full md:w-2/3">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Akun</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengguna</label>
                        <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-gray-50 border-gray-300 rounded-md shadow-sm text-gray-700" disabled>
                        <p class="text-xs text-gray-500 mt-1">Nama pengguna tidak dapat diubah</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" class="w-full bg-gray-50 border-gray-300 rounded-md shadow-sm text-gray-700" disabled>
                        <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIM/NPM</label>
                        <input type="text" value="{{ Auth::user()->nim }}" class="w-full bg-gray-50 border-gray-300 rounded-md shadow-sm text-gray-700" disabled>
                        <p class="text-xs text-gray-500 mt-1">NIM/NPM tidak dapat diubah</p>
                    </div>
                </div>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Pribadi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nama_lengkap', Auth::user()->name) }}" required>
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
        
        // Untuk preview foto yang dipilih
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('foto-preview');
        
        fotoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Hapus konten preview sebelumnya
                    fotoPreview.innerHTML = '';
                    
                    // Hapus class background dan tambahkan gambar baru
                    fotoPreview.classList.remove('bg-blue-100', 'flex', 'items-center', 'justify-center');
                    
                    // Buat elemen gambar baru
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Foto Profil Preview';
                    img.classList.add('w-40', 'h-40', 'rounded-full', 'object-cover', 'mx-auto');
                    
                    // Tambahkan gambar ke container preview
                    fotoPreview.appendChild(img);
                };
                
                // Tampilkan indikator loading
                fotoPreview.innerHTML = '<div class="w-full h-full flex items-center justify-center"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i></div>';
                
                // Baca file sebagai URL data
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Initialize tahun masuk validation on page load
        if (tahunMasukSelect.value) {
            tahunMasukSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush