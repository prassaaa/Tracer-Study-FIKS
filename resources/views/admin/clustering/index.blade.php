@extends('layouts.app')

@section('title', 'Daftar Hasil Clustering')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Hasil Clustering</h1>
        <div>
            <a href="{{ route('admin.clustering.dashboard') }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 mr-2">
                <i class="fas fa-chart-line mr-2"></i> Dashboard Analisis
            </a>
            <a href="{{ route('admin.clustering.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Proses Clustering Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Proses
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Cluster
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu Proses
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Program Studi
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($hasilClusterings as $hasil)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $hasil->nama_proses }}</div>
                                <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($hasil->deskripsi, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $hasil->jumlah_cluster }} cluster</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($hasil->waktu_proses)->format('d M Y H:i') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($hasil->waktu_proses)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if(isset($hasil->parameter['program_studi_id']))
                                        {{ \App\Models\ProgramStudi::find($hasil->parameter['program_studi_id'])->nama ?? 'Semua Program Studi' }}
                                    @else
                                        Semua Program Studi
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.clustering.show', $hasil->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.clustering.analisis', $hasil->id) }}" class="text-purple-600 hover:text-purple-900 mr-3">
                                    <i class="fas fa-chart-pie"></i>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900 delete-btn" data-id="{{ $hasil->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $hasil->id }}" action="{{ route('admin.clustering.destroy', $hasil->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($hasilClusterings->count() == 0)
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data hasil clustering.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $hasilClusterings->links() }}
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
                
                if (confirm('Apakah Anda yakin ingin menghapus hasil clustering ini?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush