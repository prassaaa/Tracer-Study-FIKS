@extends('layouts.app')

@section('title', 'Detail Pendidikan Lanjut')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pendidikan Lanjut</h1>
        <div>
            <a href="{{ route('alumni.pendidikan.edit', $pendidikan->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('alumni.pendidikan.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex flex-col md:flex-row md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $pendidikan->jenjang }} {{ $pendidikan->program_studi }}</h2>
                    <p class="text-lg text-gray-600">{{ $pendidikan->institusi }}</p>
                </div>
                <div class="mt-4 md:mt-0 md:text-right">
                    <p class="text-gray-700">
                        <i class="far fa-calendar-alt mr-2"></i>
                        {{ $pendidikan->tahun_masuk }} - {{ $pendidikan->tahun_lulus ?: 'Sekarang' }}
                    </p>
                    
                    @if($pendidikan->sedang_berjalan)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                            <i class="fas fa-check-circle mr-1"></i> Sedang Berjalan
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            <i class="fas fa-graduation-cap mr-1"></i> Selesai
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Keterangan</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700">{{ $pendidikan->keterangan ?: 'Tidak ada keterangan' }}</p>
            </div>
            
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Durasi Pendidikan</td>
                        <td class="py-2 text-gray-800">
                            @php
                                $durasiTahun = $pendidikan->tahun_lulus 
                                    ? ($pendidikan->tahun_lulus - $pendidikan->tahun_masuk) 
                                    : (date('Y') - $pendidikan->tahun_masuk);
                            @endphp
                            {{ $durasiTahun }} tahun
                            @if($pendidikan->sedang_berjalan)
                                (masih berlangsung)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Dibuat pada</td>
                        <td class="py-2 text-gray-800">{{ $pendidikan->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Terakhir diperbarui</td>
                        <td class="py-2 text-gray-800">{{ $pendidikan->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection