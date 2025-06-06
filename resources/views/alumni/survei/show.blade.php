@extends('layouts.app')

@section('title', 'Isi Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $survei->judul }}</h1>
        <a href="{{ route('alumni.survei.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi Survei</h2>
            <p class="text-gray-700">{{ $survei->deskripsi }}</p>
            <div class="mt-4 flex flex-wrap gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="far fa-calendar-alt mr-2"></i> Periode: {{ \Carbon\Carbon::parse($survei->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    <i class="far fa-question-circle mr-2"></i> {{ $survei->pertanyaanSurvei->count() }} Pertanyaan
                </span>
            </div>
        </div>
        
        <form action="{{ route('alumni.survei.store', $survei->id) }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="space-y-8">
                    @foreach($survei->pertanyaanSurvei->sortBy('urutan') as $index => $pertanyaan)
                        <div class="bg-gray-50 p-5 rounded-lg">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $index + 1 }}. {{ $pertanyaan->pertanyaan }}</h3>
                                @if($pertanyaan->wajib)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Wajib
                                    </span>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                @switch($pertanyaan->tipe)
                                    @case('text')
                                        <textarea name="jawaban[{{ $pertanyaan->id }}]" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Ketik jawaban Anda di sini..." {{ $pertanyaan->wajib ? 'required' : '' }}>{{ $jawaban[$pertanyaan->id] ?? '' }}</textarea>
                                        @break
                                        
                                    @case('radio')
                                        <div class="space-y-3">
                                            @foreach($pertanyaan->pilihan as $pilihan)
                                                <label class="flex items-start">
                                                    <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $pilihan }}" class="mt-1 border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ ($jawaban[$pertanyaan->id] ?? '') == $pilihan ? 'checked' : '' }} {{ $pertanyaan->wajib ? 'required' : '' }}>
                                                    <span class="ml-3 text-gray-700">{{ $pilihan }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @break
                                        
                                    @case('checkbox')
                                        <div class="space-y-3">
                                            @php
                                                $checkedValues = isset($jawaban[$pertanyaan->id]) ? explode(', ', $jawaban[$pertanyaan->id]) : [];
                                            @endphp
                                            
                                            @foreach($pertanyaan->pilihan as $pilihan)
                                                <label class="flex items-start">
                                                    <input type="checkbox" name="jawaban[{{ $pertanyaan->id }}][]" value="{{ $pilihan }}" class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ in_array($pilihan, $checkedValues) ? 'checked' : '' }} {{ $pertanyaan->wajib ? 'required' : '' }}>
                                                    <span class="ml-3 text-gray-700">{{ $pilihan }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @break
                                        
                                    @case('select')
                                        <select name="jawaban[{{ $pertanyaan->id }}]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" {{ $pertanyaan->wajib ? 'required' : '' }}>
                                            <option value="">-- Pilih Jawaban --</option>
                                            @foreach($pertanyaan->pilihan as $pilihan)
                                                <option value="{{ $pilihan }}" {{ ($jawaban[$pertanyaan->id] ?? '') == $pilihan ? 'selected' : '' }}>{{ $pilihan }}</option>
                                            @endforeach
                                        </select>
                                        @break
                                        
                                    @default
                                        <p class="text-red-600">Tipe pertanyaan tidak didukung</p>
                                @endswitch
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="p-6 bg-gray-50 border-t">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Jawaban Anda akan sangat membantu untuk evaluasi dan pengembangan kualitas pendidikan.
                    </p>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> Simpan Jawaban
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxGroups = document.querySelectorAll('input[type="checkbox"][required]');
        const form = document.querySelector('form');
        
        // Remove required attribute from checkboxes (HTML5 validation doesn't work well with checkbox groups)
        checkboxGroups.forEach(checkbox => {
            checkbox.removeAttribute('required');
        });
        
        // Add custom validation for checkbox groups
        form.addEventListener('submit', function(event) {
            const checkboxGroups = {};
            
            // Group checkboxes by name
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                const name = checkbox.getAttribute('name');
                if (!checkboxGroups[name]) {
                    checkboxGroups[name] = [];
                }
                checkboxGroups[name].push(checkbox);
            });
            
            // Check if any checkbox in required groups is checked
            for (const name in checkboxGroups) {
                const group = checkboxGroups[name];
                const isRequired = group.some(checkbox => checkbox.hasAttribute('data-required'));
                
                if (isRequired) {
                    const isChecked = group.some(checkbox => checkbox.checked);
                    
                    if (!isChecked) {
                        event.preventDefault();
                        alert('Mohon pilih setidaknya satu opsi untuk pertanyaan yang wajib diisi.');
                        return;
                    }
                }
            }
        });
    });
</script>
@endpush