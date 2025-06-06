@extends('layouts.app')

@section('title', 'Detail Hasil Clustering')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Hasil Clustering</h1>
        <div>
            <a href="{{ route('admin.clustering.analisis', $hasilClustering->id) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 mr-2">
                <i class="fas fa-chart-pie mr-2"></i> Analisis
            </a>
            <a href="{{ route('admin.clustering.export', $hasilClustering->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 mr-2">
                <i class="fas fa-file-export mr-2"></i> Export
            </a>
            <a href="{{ route('admin.clustering.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Clustering</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-500">Nama Proses</p>
                <p class="text-lg font-medium">{{ $hasilClustering->nama_proses }}</p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-500">Jumlah Cluster</p>
                <p class="text-lg font-medium">{{ $hasilClustering->jumlah_cluster }} cluster</p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-500">Waktu Proses</p>
                <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($hasilClustering->waktu_proses)->format('d M Y H:i') }}</p>
            </div>
            <div class="border rounded-lg p-4 md:col-span-3">
                <p class="text-sm text-gray-500">Deskripsi</p>
                <p class="text-base">{{ $hasilClustering->deskripsi ?: 'Tidak ada deskripsi' }}</p>
            </div>
            <div class="border rounded-lg p-4 md:col-span-3">
                <p class="text-sm text-gray-500">Parameter</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                    <div>
                        <span class="text-gray-600 font-medium">Program Studi:</span>
                        <span class="text-gray-800">
                            @if(isset($hasilClustering->parameter['program_studi_id']))
                                {{ \App\Models\ProgramStudi::find($hasilClustering->parameter['program_studi_id'])->nama ?? 'Semua Program Studi' }}
                            @else
                                Semua Program Studi
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 font-medium">Tahun Lulus:</span>
                        <span class="text-gray-800">
                            @if(isset($hasilClustering->parameter['tahun_lulus_awal']) && isset($hasilClustering->parameter['tahun_lulus_akhir']))
                                {{ $hasilClustering->parameter['tahun_lulus_awal'] }} - {{ $hasilClustering->parameter['tahun_lulus_akhir'] }}
                            @elseif(isset($hasilClustering->parameter['tahun_lulus_awal']))
                                ≥ {{ $hasilClustering->parameter['tahun_lulus_awal'] }}
                            @elseif(isset($hasilClustering->parameter['tahun_lulus_akhir']))
                                ≤ {{ $hasilClustering->parameter['tahun_lulus_akhir'] }}
                            @else
                                Semua Tahun
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 font-medium">Atribut:</span>
                        <span class="text-gray-800">
                            @if(isset($hasilClustering->parameter['atribut']))
                                {{ implode(', ', array_map(function($attr) {
                                    $labels = [
                                        'gaji' => 'Gaji',
                                        'bidang_pekerjaan' => 'Bidang Pekerjaan',
                                        'waktu_tunggu' => 'Waktu Tunggu',
                                        'jenjang_pendidikan' => 'Jenjang Pendidikan'
                                    ];
                                    return $labels[$attr] ?? $attr;
                                }, $hasilClustering->parameter['atribut'])) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Hasil Clustering</h2>
            <div class="flex gap-2">
                @foreach($clusterGroups as $clusterId => $members)
                    <button type="button" class="cluster-filter px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200" data-cluster="{{ $clusterId }}">
                        Cluster {{ $clusterId + 1 }} ({{ $members->count() }})
                    </button>
                @endforeach
                <button type="button" class="cluster-filter px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 active" data-cluster="all">
                    Semua
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cluster
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIM
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Program Studi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tahun Lulus
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($clusterGroups as $clusterId => $members)
                        @foreach($members as $member)
                            <tr class="cluster-row" data-cluster="{{ $clusterId }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Cluster {{ $clusterId + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $member->alumniProfile->nim }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $member->alumniProfile->nama_lengkap }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $member->alumniProfile->programStudi->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $member->alumniProfile->tahun_lulus }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.alumni.show', $member->alumniProfile->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    @if($hasilClustering->clusteringAlumni->count() == 0)
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data hasil clustering.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clusterFilters = document.querySelectorAll('.cluster-filter');
        const clusterRows = document.querySelectorAll('.cluster-row');
        
        clusterFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                clusterFilters.forEach(f => f.classList.remove('active', 'bg-blue-600', 'text-white'));
                clusterFilters.forEach(f => f.classList.add('bg-blue-100', 'text-blue-800'));
                
                // Add active class to clicked filter
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-blue-100', 'text-blue-800');
                
                const cluster = this.getAttribute('data-cluster');
                
                // Show/hide rows based on filter
                clusterRows.forEach(row => {
                    if (cluster === 'all' || row.getAttribute('data-cluster') === cluster) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });
    });
</script>
@endpush