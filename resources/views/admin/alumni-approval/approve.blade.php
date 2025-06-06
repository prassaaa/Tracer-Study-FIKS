@extends('layouts.app')

@section('title', 'Persetujuan Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Persetujuan Alumni</h1>
        <a href="{{ route('admin.alumni-approval.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Lengkapi Data Alumni</h2>
        
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Pendaftaran</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p><strong>Tanggal Pendaftaran:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.alumni-approval.storeApprove', $user->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NPM <span class="text-red-600">*</span></label>
                        <input type="text" name="nim" id="nim" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nim', $user->nim) }}" required readonly>
                        <p class="mt-1 text-xs text-gray-500">NPM diambil dari data pendaftaran dan tidak dapat diubah</p>
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('nama_lengkap', $user->name) }}" required>
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-600">*</span></label>
                        <input type="text" name="email" id="email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <i class="fas fa-check mr-2"></i> Setujui dan Buat Profil
                </button>
            </div>
        </form>
    </div>
@endsection