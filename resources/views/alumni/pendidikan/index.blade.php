@extends('layouts.app')

@section('title', 'Pendidikan Lanjut')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pendidikan Lanjut</h1>
        <a href="{{ route('alumni.pendidikan.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Pendidikan
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if(count($pendidikanLanjut) > 0)
            <div class="divide-y divide-gray-200">
                @foreach($pendidikanLanjut as $pendidikan)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $pendidikan->jenjang }} {{ $pendidikan->program_studi }}</h2>
                                <p class="text-md text-gray-600">{{ $pendidikan->institusi }}</p>
                                
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    <span>
                                        {{ $pendidikan->tahun_masuk }} - 
                                        {{ $pendidikan->tahun_lulus ?: 'Sekarang' }}
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    @if($pendidikan->sedang_berjalan)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Sedang Berjalan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-graduation-cap mr-1"></i> Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('alumni.pendidikan.show', $pendidikan->id) }}" class="px-3 py-1 text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('alumni.pendidikan.edit', $pendidikan->id) }}" class="px-3 py-1 text-yellow-600 bg-yellow-100 rounded-md hover:bg-yellow-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="px-3 py-1 text-red-600 bg-red-100 rounded-md hover:bg-red-200 delete-btn" data-id="{{ $pendidikan->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $pendidikan->id }}" action="{{ route('alumni.pendidikan.destroy', $pendidikan->id) }}" method="POST" class="hidden">
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
                    <i class="fas fa-book text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data Pendidikan Lanjut</h2>
                <p class="text-gray-600 mb-6">Anda belum menambahkan riwayat pendidikan lanjut. Jika Anda melanjutkan pendidikan, tambahkan data pendidikan lanjut untuk melengkapi profil Anda.</p>
                <a href="{{ route('alumni.pendidikan.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Pendidikan
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
                
                if (confirm('Apakah Anda yakin ingin menghapus data pendidikan lanjut ini?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush