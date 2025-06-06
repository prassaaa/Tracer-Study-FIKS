@extends('layouts.app')

@section('title', 'Detail Riwayat Pekerjaan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Riwayat Pekerjaan</h1>
        <div>
            <a href="{{ route('alumni.riwayat-pekerjaan.edit', $riwayatPekerjaan->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('alumni.riwayat-pekerjaan.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex flex-col md:flex-row md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $riwayatPekerjaan->posisi }}</h2>
                    <p class="text-lg text-gray-600">{{ $riwayatPekerjaan->nama_perusahaan }}</p>
                    <p class="text-gray-500">{{ $riwayatPekerjaan->lokasi ?? '-' }}</p>
                </div>
                <div class="mt-4 md:mt-0 md:text-right">
                    <p class="text-gray-700">
                        <i class="far fa-calendar-alt mr-2"></i>
                        {{ \Carbon\Carbon::parse($riwayatPekerjaan->tanggal_mulai)->format('d M Y') }} - 
                        {{ $riwayatPekerjaan->tanggal_selesai ? \Carbon\Carbon::parse($riwayatPekerjaan->tanggal_selesai)->format('d M Y') : 'Sekarang' }}
                    </p>
                    
                    @if($riwayatPekerjaan->pekerjaan_saat_ini)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                            <i class="fas fa-check-circle mr-1"></i> Pekerjaan Saat Ini
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pekerjaan</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Bidang Pekerjaan</td>
                        <td class="py-2 text-gray-800">{{ $riwayatPekerjaan->bidang_pekerjaan }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Gaji</td>
                        <td class="py-2 text-gray-800">{{ $riwayatPekerjaan->gaji ? 'Rp ' . number_format($riwayatPekerjaan->gaji, 0, ',', '.') . '/bulan' : 'Tidak Diisi' }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Tanggal Mulai</td>
                        <td class="py-2 text-gray-800">{{ \Carbon\Carbon::parse($riwayatPekerjaan->tanggal_mulai)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Tanggal Selesai</td>
                        <td class="py-2 text-gray-800">{{ $riwayatPekerjaan->tanggal_selesai ? \Carbon\Carbon::parse($riwayatPekerjaan->tanggal_selesai)->format('d M Y') : 'Sekarang (Masih Bekerja)' }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Durasi</td>
                        <td class="py-2 text-gray-800">
                            @php
                                $start = \Carbon\Carbon::parse($riwayatPekerjaan->tanggal_mulai);
                                $end = $riwayatPekerjaan->tanggal_selesai ? \Carbon\Carbon::parse($riwayatPekerjaan->tanggal_selesai) : \Carbon\Carbon::now();
                                $diffInMonths = $start->diffInMonths($end);
                                $years = floor($diffInMonths / 12);
                                $months = $diffInMonths % 12;
                            @endphp
                            
                            @if($years > 0)
                                {{ $years }} tahun
                            @endif
                            
                            @if($months > 0)
                                {{ $years > 0 ? ' ' : '' }}{{ $months }} bulan
                            @endif
                            
                            @if($years == 0 && $months == 0)
                                < 1 bulan
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Deskripsi Pekerjaan</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-line">{{ $riwayatPekerjaan->deskripsi_pekerjaan ?: 'Tidak ada deskripsi pekerjaan' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection