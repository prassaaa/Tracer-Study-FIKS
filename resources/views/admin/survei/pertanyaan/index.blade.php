@extends('layouts.app')

@section('title', 'Daftar Pertanyaan Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pertanyaan Survei: {{ $survei->judul }}</h1>
            <p class="text-gray-600">{{ $survei->deskripsi }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.survei.pertanyaan.create', $survei->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
            </a>
            <a href="{{ route('admin.survei.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-base text-gray-800">{{ \Carbon\Carbon::parse($survei->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-base text-gray-800">
                        @if($survei->aktif)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($survei->pertanyaanSurvei->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($survei->pertanyaanSurvei->sortBy('urutan') as $index => $pertanyaan)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $index + 1 }}. {{ $pertanyaan->pertanyaan }}</h2>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <span class="mr-4">
                                        <i class="fas fa-sort-numeric-down mr-1"></i> Urutan: {{ $pertanyaan->urutan }}
                                    </span>
                                    <span>
                                        <i class="fas fa-list-ul mr-1"></i> Tipe: 
                                        @switch($pertanyaan->tipe)
                                            @case('text')
                                                <span class="text-blue-600">Text (Isian)</span>
                                                @break
                                            @case('radio')
                                                <span class="text-green-600">Radio (Pilihan Tunggal)</span>
                                                @break
                                            @case('checkbox')
                                                <span class="text-purple-600">Checkbox (Pilihan Ganda)</span>
                                                @break
                                            @case('select')
                                                <span class="text-orange-600">Select (Dropdown)</span>
                                                @break
                                            @default
                                                <span>{{ $pertanyaan->tipe }}</span>
                                        @endswitch
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    @if($pertanyaan->wajib)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-asterisk mr-1"></i> Wajib
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-check mr-1"></i> Opsional
                                        </span>
                                    @endif
                                </div>
                                
                                @if($pertanyaan->tipe != 'text' && !empty($pertanyaan->pilihan))
                                    <div class="mt-4 bg-gray-50 p-3 rounded-md">
                                        <p class="text-sm text-gray-600 mb-2">Pilihan Jawaban:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($pertanyaan->pilihan as $pilihan)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    {{ $pilihan }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.survei.pertanyaan.edit', [$survei->id, $pertanyaan->id]) }}" class="px-3 py-1 text-yellow-600 bg-yellow-100 rounded-md hover:bg-yellow-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="px-3 py-1 text-red-600 bg-red-100 rounded-md hover:bg-red-200 delete-btn" data-id="{{ $pertanyaan->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $pertanyaan->id }}" action="{{ route('admin.survei.pertanyaan.destroy', [$survei->id, $pertanyaan->id]) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center">
                <div class="bg-gray-50 rounded-lg p-6 inline-block mb-4">
                    <i class="fas fa-question-circle text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Pertanyaan</h2>
                <p class="text-gray-600 mb-6">Belum ada pertanyaan yang dibuat untuk survei ini. Tambahkan pertanyaan untuk memulai.</p>
                <a href="{{ route('admin.survei.pertanyaan.create', $survei->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush