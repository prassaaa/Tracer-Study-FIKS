@extends('layouts.app')

@section('title', 'Profil Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Profil Alumni</h1>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-md">
            <p class="font-medium">{{ session('info') }}</p>
        </div>
    @endif

    @if(Auth::user()->status === 'pending')
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-center py-8">
                <div class="bg-yellow-50 rounded-lg p-6 inline-block mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Akun Dalam Peninjauan</h2>
                <p class="text-gray-600 mb-6">Akun Anda masih menunggu persetujuan admin. Anda akan dapat mengedit profil setelah akun Anda disetujui.</p>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    @elseif(Auth::user()->status === 'rejected')
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-center py-8">
                <div class="bg-red-50 rounded-lg p-6 inline-block mb-4">
                    <i class="fas fa-times-circle text-red-500 text-4xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Akun Ditolak</h2>
                <p class="text-gray-600 mb-6">Pendaftaran Anda telah ditolak. Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    @elseif(!$profile && Auth::user()->status === 'approved')
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-center py-8">
                <div class="bg-yellow-50 rounded-lg p-6 inline-block mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Profil Alumni Belum Tersedia</h2>
                <p class="text-gray-600 mb-6">Profil Anda belum dibuat. Silakan buat profil Anda sekarang.</p>
                <a href="{{ route('alumni.profile.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-user-plus mr-2"></i> Buat Profil
                </a>
            </div>
        </div>
    @elseif($profile)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('alumni.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/3 mb-6 md:mb-0">
                        <div class="text-center">
                            @if($profile->foto)
                                <img src="{{ asset('storage/alumni/' . $profile->foto) }}" alt="Foto Profil" class="w-40 h-40 rounded-full object-cover mx-auto mb-4">
                            @else
                                <div class="w-40 h-40 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user text-blue-500 text-5xl"></i>
                                </div>
                            @endif
                            <label for="foto" class="cursor-pointer text-blue-600 hover:text-blue-800">
                                <span class="px-4 py-2 bg-blue-50 rounded-md"><i class="fas fa-camera mr-2"></i> Ubah Foto</span>
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
                            <p class="text-xs text-gray-500 mt-1">Untuk mengubah email, silakan hubungi administrator</p>
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
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-600">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-600">*</span></label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('tanggal_lahir', $profile->tanggal_lahir) }}" required>
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="program_studi_id" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                        <select name="program_studi_id" id="program_studi_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Pilih Program Studi</option>
                            @foreach(\App\Models\ProgramStudi::all() as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id', $profile->program_studi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
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
                                <option value="{{ $year }}" {{ old('tahun_masuk', $profile->tahun_masuk) == $year ? 'selected' : '' }}>{{ $year }}</option>
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
                                <option value="{{ $year }}" {{ old('tahun_lulus', $profile->tahun_lulus) == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('tahun_lulus')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('no_telepon', $profile->no_telepon) }}">
                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('alamat', $profile->alamat) }}</textarea>
                    @error('alamat')
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
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto');
    
    if (fotoInput) {
        fotoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                // Find the image container (the parent div that holds either the image or the icon)
                const imageContainer = fotoInput.closest('.text-center');
                
                // Get the current image or icon container
                let fotoPreview = imageContainer.querySelector('img.rounded-full');
                const iconContainer = imageContainer.querySelector('.rounded-full.bg-blue-100');
                
                // If there's no image but there's an icon, replace the icon with an image
                if (!fotoPreview && iconContainer) {
                    // Remove the icon container
                    iconContainer.remove();
                    
                    // Create new image element
                    fotoPreview = document.createElement('img');
                    fotoPreview.className = 'w-40 h-40 rounded-full object-cover mx-auto mb-4';
                    fotoPreview.alt = 'Foto Profil';
                    
                    // Insert the new image at the beginning of the container
                    imageContainer.insertBefore(fotoPreview, imageContainer.firstChild);
                }
                // If neither exists (shouldn't happen but just in case), create a new image
                else if (!fotoPreview) {
                    // Create new image element
                    fotoPreview = document.createElement('img');
                    fotoPreview.className = 'w-40 h-40 rounded-full object-cover mx-auto mb-4';
                    fotoPreview.alt = 'Foto Profil';
                    
                    // Insert the new image at the beginning of the container
                    imageContainer.insertBefore(fotoPreview, imageContainer.firstChild);
                }
                
                // Set the image source
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                };
                
                reader.readAsDataURL(this.files[0]);
                
                // Create or update the notification message
                let fileSelectedMessage = imageContainer.querySelector('.text-sm.text-green-600');
                
                if (!fileSelectedMessage) {
                    fileSelectedMessage = document.createElement('p');
                    fileSelectedMessage.className = 'text-sm text-green-600 mt-2';
                    
                    // Insert after the input label
                    const fileInputLabel = fotoInput.parentNode;
                    fileInputLabel.insertAdjacentElement('afterend', fileSelectedMessage);
                }
                
                fileSelectedMessage.textContent = 'Foto baru dipilih (belum disimpan)';
            }
        });
    }
});
</script>
@endpush