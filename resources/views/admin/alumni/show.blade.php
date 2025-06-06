@extends('layouts.app')

@section('title', 'Detail Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Alumni</h1>
        <div>
            <a href="{{ route('admin.alumni.edit', $alumni->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b">
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/4 flex justify-center mb-6 md:mb-0">
                    @if($alumni->foto)
                        <img src="{{ asset('storage/alumni/' . $alumni->foto) }}" alt="{{ $alumni->nama_lengkap }}" class="w-40 h-40 rounded-full object-cover">
                    @else
                        <div class="w-40 h-40 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-500 text-5xl"></i>
                        </div>
                    @endif
                </div>
                <div class="w-full md:w-3/4">
                    <div class="flex justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $alumni->nama_lengkap }}</h2>
                            <p class="text-gray-600">{{ $alumni->nim }}</p>
                        </div>
                        @php
                            $pekerjaan = $alumni->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                            $pendidikan = $alumni->pendidikanLanjut()->where('sedang_berjalan', true)->first();
                        @endphp
                        
                        <div class="flex flex-wrap gap-2">
                            @if($pekerjaan)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    <i class="fas fa-briefcase mr-1"></i> Bekerja
                                </span>
                            @endif
                            
                            @if($pendidikan)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    <i class="fas fa-graduation-cap mr-1"></i> Studi Lanjut
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 mt-4">
                        <div>
                            <p class="text-sm text-gray-500">Program Studi</p>
                            <p class="text-base text-gray-800">{{ $alumni->programStudi->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fakultas</p>
                            <p class="text-base text-gray-800">{{ $alumni->programStudi->fakultas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Masuk - Lulus</p>
                            <p class="text-base text-gray-800">{{ $alumni->tahun_masuk }} - {{ $alumni->tahun_lulus }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base text-gray-800">{{ $alumni->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Kelamin</p>
                            <p class="text-base text-gray-800">{{ $alumni->jenis_kelamin }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Lahir</p>
                            <p class="text-base text-gray-800">{{ \Carbon\Carbon::parse($alumni->tanggal_lahir)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nomor Telepon</p>
                            <p class="text-base text-gray-800">{{ $alumni->no_telepon ?: '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="text-base text-gray-800">{{ $alumni->alamat ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Riwayat Pekerjaan -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Pekerjaan</h3>
                <span class="text-sm text-gray-500">{{ $alumni->riwayatPekerjaan->count() }} data</span>
            </div>
            <div class="p-6">
                @if($alumni->riwayatPekerjaan->count() > 0)
                    <div class="space-y-6">
                        @foreach($alumni->riwayatPekerjaan->sortByDesc('tanggal_mulai') as $pekerjaan)
                            <div class="border-l-4 {{ $pekerjaan->pekerjaan_saat_ini ? 'border-green-500' : 'border-gray-300' }} pl-4">
                                <h4 class="text-lg font-medium text-gray-800">{{ $pekerjaan->posisi }}</h4>
                                <p class="text-gray-600">{{ $pekerjaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-500">{{ $pekerjaan->lokasi }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($pekerjaan->tanggal_mulai)->format('M Y') }} - 
                                    {{ $pekerjaan->tanggal_selesai ? \Carbon\Carbon::parse($pekerjaan->tanggal_selesai)->format('M Y') : 'Sekarang' }}
                                </p>
                                @if($pekerjaan->gaji)
                                    <p class="text-sm text-gray-600 mt-1">Gaji: Rp {{ number_format($pekerjaan->gaji, 0, ',', '.') }}</p>
                                @endif
                                @if($pekerjaan->pekerjaan_saat_ini)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                        Pekerjaan Saat Ini
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-briefcase text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada data riwayat pekerjaan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pendidikan Lanjut -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Pendidikan Lanjut</h3>
                <span class="text-sm text-gray-500">{{ $alumni->pendidikanLanjut->count() }} data</span>
            </div>
            <div class="p-6">
                @if($alumni->pendidikanLanjut->count() > 0)
                    <div class="space-y-6">
                        @foreach($alumni->pendidikanLanjut->sortByDesc('tahun_masuk') as $pendidikan)
                            <div class="border-l-4 {{ $pendidikan->sedang_berjalan ? 'border-blue-500' : 'border-gray-300' }} pl-4">
                                <h4 class="text-lg font-medium text-gray-800">{{ $pendidikan->jenjang }} {{ $pendidikan->program_studi }}</h4>
                                <p class="text-gray-600">{{ $pendidikan->institusi }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $pendidikan->tahun_masuk }} - {{ $pendidikan->tahun_lulus ?: 'Sekarang' }}
                                </p>
                                @if($pendidikan->sedang_berjalan)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                                        Sedang Berjalan
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada data pendidikan lanjut.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Survei yang Sudah Diisi -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Partisipasi Survei</h3>
        </div>
        <div class="p-6">
            @php
                $jawaban = \App\Models\JawabanSurvei::where('alumni_profile_id', $alumni->id)
                    ->select('pertanyaan_survei_id')
                    ->distinct()
                    ->get();
                
                $surveiIds = \App\Models\PertanyaanSurvei::whereIn('id', $jawaban->pluck('pertanyaan_survei_id'))
                    ->select('survei_id')
                    ->distinct()
                    ->pluck('survei_id');
                
                $surveis = \App\Models\Survei::whereIn('id', $surveiIds)->get();
            @endphp
            
            @if($surveis->count() > 0)
                <div class="space-y-4">
                    @foreach($surveis as $survei)
                        <div class="p-4 border rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-base font-medium text-gray-800">{{ $survei->judul }}</h4>
                                    <p class="text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($survei->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Berpartisipasi
                                </span>
                            </div>
                            
                            @php
                                $pertanyaanIds = \App\Models\PertanyaanSurvei::where('survei_id', $survei->id)->pluck('id');
                                $jawabanCount = \App\Models\JawabanSurvei::where('alumni_profile_id', $alumni->id)
                                    ->whereIn('pertanyaan_survei_id', $pertanyaanIds)
                                    ->count();
                                $totalPertanyaan = $pertanyaanIds->count();
                                $persentase = $totalPertanyaan > 0 ? round(($jawabanCount / $totalPertanyaan) * 100) : 0;
                            @endphp
                            
                            <div class="mt-2">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Jawaban Terisi</span>
                                    <span>{{ $jawabanCount }}/{{ $totalPertanyaan }} ({{ $persentase }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $persentase }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-poll-h text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">Belum berpartisipasi dalam survei apapun.</p>
                </div>
            @endif
        </div>
    </div>
@endsection