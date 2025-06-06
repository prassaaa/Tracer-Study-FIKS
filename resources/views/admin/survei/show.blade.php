@extends('layouts.app')

@section('title', 'Detail Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Survei</h1>
        <div>
            <a href="{{ route('admin.survei.pertanyaan', $survei->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                <i class="fas fa-list-ol mr-2"></i> Kelola Pertanyaan
            </a>
            <a href="{{ route('admin.survei.edit', $survei->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.survei.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $survei->judul }}</h2>
            <p class="text-gray-600">{{ $survei->deskripsi }}</p>
            
            <div class="mt-4 flex flex-wrap gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="far fa-calendar-alt mr-2"></i> Periode: {{ \Carbon\Carbon::parse($survei->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}
                </span>
                
                @if($survei->aktif)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i> Aktif
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-2"></i> Tidak Aktif
                    </span>
                @endif
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    <i class="far fa-question-circle mr-2"></i> {{ $survei->pertanyaanSurvei->count() }} Pertanyaan
                </span>
            </div>
        </div>
        
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Responden</h3>
            
            @php
                $pertanyaanIds = $survei->pertanyaanSurvei->pluck('id');
                $totalJawaban = \App\Models\JawabanSurvei::whereIn('pertanyaan_survei_id', $pertanyaanIds)->count();
                $totalResponden = \App\Models\JawabanSurvei::whereIn('pertanyaan_survei_id', $pertanyaanIds)
                    ->select('alumni_profile_id')
                    ->distinct()
                    ->count();
                $totalAlumni = \App\Models\AlumniProfile::count();
                $persentaseResponden = $totalAlumni > 0 ? round(($totalResponden / $totalAlumni) * 100, 1) : 0;
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Total Responden</div>
                    <div class="text-xl font-semibold text-gray-800">{{ $totalResponden }} Alumni</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $persentaseResponden }}% dari total alumni</div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Total Jawaban</div>
                    <div class="text-xl font-semibold text-gray-800">{{ $totalJawaban }} Jawaban</div>
                    <div class="text-xs text-gray-500 mt-1">
                        @if($survei->pertanyaanSurvei->count() > 0 && $totalResponden > 0)
                            Rata-rata {{ round($totalJawaban / $totalResponden, 1) }} jawaban per responden
                        @else
                            0 jawaban per responden
                        @endif
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Waktu Tersisa</div>
                    @php
                        $now = \Carbon\Carbon::now();
                        $end = \Carbon\Carbon::parse($survei->tanggal_selesai);
                        $sisaHari = $now->lte($end) ? $now->diffInDays($end) : 0;
                    @endphp
                    
                    @if($now->lt(\Carbon\Carbon::parse($survei->tanggal_mulai)))
                        <div class="text-xl font-semibold text-yellow-600">Belum Dimulai</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Dimulai dalam {{ $now->diffInDays(\Carbon\Carbon::parse($survei->tanggal_mulai)) }} hari
                        </div>
                    @elseif($now->gt($end))
                        <div class="text-xl font-semibold text-red-600">Sudah Berakhir</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Berakhir {{ $now->diffInDays($end) }} hari yang lalu
                        </div>
                    @else
                        <div class="text-xl font-semibold text-green-600">{{ $sisaHari }} Hari</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Berakhir pada {{ $end->format('d M Y') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pertanyaan</h3>
        </div>
        
        @if($survei->pertanyaanSurvei->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($survei->pertanyaanSurvei->sortBy('urutan') as $index => $pertanyaan)
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-base font-semibold text-gray-800">{{ $index + 1 }}. {{ $pertanyaan->pertanyaan }}</h4>
                                <div class="mt-2 text-sm text-gray-500">
                                    <span class="mr-4">
                                        <i class="fas fa-list-ul mr-1"></i> Tipe: 
                                        @switch($pertanyaan->tipe)
                                            @case('text')
                                                Text (Isian)
                                                @break
                                            @case('radio')
                                                Radio (Pilihan Tunggal)
                                                @break
                                            @case('checkbox')
                                                Checkbox (Pilihan Ganda)
                                                @break
                                            @case('select')
                                                Select (Dropdown)
                                                @break
                                            @default
                                                {{ $pertanyaan->tipe }}
                                        @endswitch
                                    </span>
                                    
                                    @if($pertanyaan->wajib)
                                        <span class="text-red-600"><i class="fas fa-asterisk mr-1"></i> Wajib</span>
                                    @else
                                        <span><i class="fas fa-check mr-1"></i> Opsional</span>
                                    @endif
                                </div>
                                
                                @if($pertanyaan->tipe != 'text' && !empty($pertanyaan->pilihan))
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-600 mb-1">Pilihan:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($pertanyaan->pilihan as $pilihan)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    {{ $pilihan }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @php
                                    $jumlahJawaban = \App\Models\JawabanSurvei::where('pertanyaan_survei_id', $pertanyaan->id)->count();
                                @endphp
                                
                                <div class="mt-3 text-sm text-gray-500">
                                    <i class="fas fa-chart-bar mr-1"></i> {{ $jumlahJawaban }} jawaban
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.survei.pertanyaan.edit', [$survei->id, $pertanyaan->id]) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500">Belum ada pertanyaan untuk survei ini.</p>
                <a href="{{ route('admin.survei.pertanyaan.create', $survei->id) }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                </a>
            </div>
        @endif
    </div>
@endsection