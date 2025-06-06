@extends('layouts.app')

@section('title', 'Daftar Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Survei</h1>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($surveis as $survei)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ $survei->judul }}</h2>
                            <p class="text-gray-600 mt-1">{{ $survei->deskripsi }}</p>
                            
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="far fa-calendar-alt mr-1"></i> Batas: {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="far fa-question-circle mr-1"></i> {{ $survei->total_pertanyaan }} Pertanyaan
                                </span>
                                
                                @if($survei->sudah_dijawab)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Sudah Diisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Belum Diisi
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center">
                                @if($survei->sudah_dijawab)
                                    <div class="flex items-center space-x-1 text-green-600 mr-4">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="text-sm">{{ $survei->pertanyaan_dijawab }}/{{ $survei->total_pertanyaan }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-1 text-yellow-600 mr-4">
                                        <i class="fas fa-clock"></i>
                                        <span class="text-sm">0/{{ $survei->total_pertanyaan }}</span>
                                    </div>
                                @endif
                                
                                <a href="{{ route('alumni.survei.show', $survei->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    @if($survei->sudah_dijawab)
                                        <i class="fas fa-edit mr-2"></i> Edit Jawaban
                                    @else
                                        <i class="fas fa-pen mr-2"></i> Isi Survei
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @if($survei->sudah_dijawab)
                    <div class="bg-gray-50 px-6 py-3 border-t">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i> Anda telah mengisi survei ini pada {{ \Carbon\Carbon::parse(\App\Models\JawabanSurvei::where('alumni_profile_id', auth()->user()->alumniProfile->id)->whereHas('pertanyaanSurvei', function($q) use($survei) { $q->where('survei_id', $survei->id); })->latest()->first()->created_at ?? now())->format('d M Y H:i') }}.
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="bg-gray-50 rounded-lg p-6 inline-block mb-4">
                    <i class="fas fa-poll-h text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Survei Aktif</h2>
                <p class="text-gray-600 mb-6">Saat ini tidak ada survei yang tersedia untuk diisi. Silakan periksa kembali nanti.</p>
            </div>
        @endforelse
    </div>
@endsection