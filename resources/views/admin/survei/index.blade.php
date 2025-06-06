@extends('layouts.app')

@section('title', 'Daftar Survei')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Survei</h1>
        <a href="{{ route('admin.survei.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Survei
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Judul
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Responden
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pertanyaan
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($surveis as $survei)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $survei->judul }}</div>
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($survei->deskripsi, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($survei->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}</div>
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $start = \Carbon\Carbon::parse($survei->tanggal_mulai);
                                    $end = \Carbon\Carbon::parse($survei->tanggal_selesai);
                                @endphp
                                
                                @if($now->lt($start))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Akan datang
                                    </span>
                                @elseif($now->gt($end))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Berlangsung
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($survei->aktif)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @php
                                        $jawabanCount = \App\Models\JawabanSurvei::whereHas('pertanyaanSurvei', function($query) use ($survei) {
                                            $query->where('survei_id', $survei->id);
                                        })->count();
                                        
                                        $alumniCount = \App\Models\JawabanSurvei::whereHas('pertanyaanSurvei', function($query) use ($survei) {
                                            $query->where('survei_id', $survei->id);
                                        })->distinct('alumni_profile_id')->count('alumni_profile_id');
                                    @endphp
                                    {{ $alumniCount }} Alumni
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $jawabanCount }} Jawaban
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $survei->pertanyaan_survei_count }} Pertanyaan</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.survei.pertanyaan', $survei->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Kelola Pertanyaan">
                                    <i class="fas fa-list-ol"></i>
                                </a>
                                <a href="{{ route('admin.survei.show', $survei->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.survei.edit', $survei->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900 delete-btn" data-id="{{ $survei->id }}" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $survei->id }}" action="{{ route('admin.survei.destroy', $survei->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($surveis->count() == 0)
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data survei.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $surveis->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                if (confirm('Apakah Anda yakin ingin menghapus survei ini?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush