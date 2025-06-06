@extends('layouts.app')

@section('title', 'Dashboard Analisis Clustering')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Analisis Clustering</h1>
        <a href="{{ route('admin.clustering.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-list mr-2"></i> Daftar Clustering
        </a>
    </div>

    @if(!isset($latestClustering) || !$latestClustering)
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <div class="bg-gray-50 rounded-lg p-6 inline-block mb-4">
                <i class="fas fa-chart-pie text-gray-400 text-4xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data Clustering</h2>
            <p class="text-gray-600 mb-6">Anda belum melakukan proses clustering. Lakukan proses clustering untuk melihat analisis data alumni.</p>
            <a href="{{ route('admin.clustering.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Proses Clustering Baru
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Clustering Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Nama Proses</p>
                    <p class="text-lg font-medium">{{ $latestClustering->nama_proses }}</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Jumlah Cluster</p>
                    <p class="text-lg font-medium">{{ $latestClustering->jumlah_cluster }} cluster</p>
                </div>
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500">Waktu Proses</p>
                    <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($latestClustering->waktu_proses)->format('d M Y H:i') }}</p>
                </div>
                <div class="border rounded-lg p-4 md:col-span-3">
                    <p class="text-sm text-gray-500">Parameter</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                        <div>
                            <span class="text-gray-600 font-medium">Program Studi:</span>
                            <span class="text-gray-800">
                                @if(isset($latestClustering->parameter['program_studi_id']))
                                    {{ \App\Models\ProgramStudi::find($latestClustering->parameter['program_studi_id'])->nama ?? 'Semua Program Studi' }}
                                @else
                                    Semua Program Studi
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Tahun Lulus:</span>
                            <span class="text-gray-800">
                                @if(isset($latestClustering->parameter['tahun_lulus_awal']) && isset($latestClustering->parameter['tahun_lulus_akhir']))
                                    {{ $latestClustering->parameter['tahun_lulus_awal'] }} - {{ $latestClustering->parameter['tahun_lulus_akhir'] }}
                                @elseif(isset($latestClustering->parameter['tahun_lulus_awal']))
                                    ≥ {{ $latestClustering->parameter['tahun_lulus_awal'] }}
                                @elseif(isset($latestClustering->parameter['tahun_lulus_akhir']))
                                    ≤ {{ $latestClustering->parameter['tahun_lulus_akhir'] }}
                                @else
                                    Semua Tahun
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Atribut:</span>
                            <span class="text-gray-800">
                                @if(isset($latestClustering->parameter['atribut']))
                                    {{ implode(', ', array_map(function($attr) {
                                        $labels = [
                                            'gaji' => 'Gaji',
                                            'bidang_pekerjaan' => 'Bidang Pekerjaan',
                                            'waktu_tunggu' => 'Waktu Tunggu',
                                            'jenjang_pendidikan' => 'Jenjang Pendidikan'
                                        ];
                                        return $labels[$attr] ?? $attr;
                                    }, $latestClustering->parameter['atribut'])) }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Cluster</h2>
                <canvas id="clusterDistributionChart" height="300"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Rata-rata Gaji per Cluster</h2>
                <canvas id="clusterSalaryChart" height="300"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Karakteristik Cluster</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cluster
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Alumni
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gaji Rata-rata
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bidang Pekerjaan Dominan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Tunggu Rata-rata
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clusterStats as $clusterId => $stats)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Cluster {{ $clusterId + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $stats['count'] }} alumni</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($stats['gaji_rata_rata'], 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @php
                                            $bidangPekerjaan = $stats['bidang_pekerjaan'];
                                            $totalCount = array_sum($bidangPekerjaan);
                                            $dominanBidang = array_keys($bidangPekerjaan, max($bidangPekerjaan))[0] ?? '-';
                                            $persentase = $totalCount > 0 ? round(max($bidangPekerjaan) / $totalCount * 100) : 0;
                                        @endphp
                                        {{ $dominanBidang }} ({{ $persentase }}%)
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach(array_slice($bidangPekerjaan, 0, 2) as $bidang => $count)
                                            {{ $bidang }}: {{ $count }} alumni
                                            @if(!$loop->last), @endif
                                        @endforeach
                                        @if(count($bidangPekerjaan) > 2)
                                            dan {{ count($bidangPekerjaan) - 2 }} bidang lainnya
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ round($stats['waktu_tunggu_rata_rata'], 1) }} bulan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Program Studi per Cluster</h2>
                <div class="space-y-6">
                    @foreach($clusterStats as $clusterId => $stats)
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Cluster {{ $clusterId + 1 }}</h3>
                            <div class="space-y-2">
                                @php
                                    $programStudi = $stats['program_studi'] ?? [];
                                    arsort($programStudi);
                                @endphp
                                
                                @forelse(array_slice($programStudi, 0, 3) as $prodi => $count)
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($count / max($programStudi)) * 100 }}%"></div>
                                        </div>
                                        <span class="ml-4 text-sm text-gray-600 whitespace-nowrap">{{ $prodi }} ({{ $count }})</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Tidak ada data</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Jenjang Pendidikan per Cluster</h2>
                <canvas id="clusterEducationChart" height="300"></canvas>
            </div>
        </div>
    @endif
@endsection

@if(isset($latestClustering) && $latestClustering)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cluster Distribution Chart
        const distributionCtx = document.getElementById('clusterDistributionChart').getContext('2d');
        const distributionChart = new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($clusterStats as $clusterId => $stats)
                        'Cluster {{ $clusterId + 1 }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($clusterStats as $stats)
                            {{ $stats['count'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(201, 203, 207, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value * 100) / total);
                                return `${label}: ${value} alumni (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Salary Chart
        const salaryCtx = document.getElementById('clusterSalaryChart').getContext('2d');
        const salaryChart = new Chart(salaryCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($clusterStats as $clusterId => $stats)
                        'Cluster {{ $clusterId + 1 }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Gaji Rata-rata (juta)',
                    data: [
                        @foreach($clusterStats as $stats)
                            {{ $stats['gaji_rata_rata'] / 1000000 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value + ' juta';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return 'Gaji Rata-rata: Rp ' + value.toFixed(2) + ' juta';
                            }
                        }
                    }
                }
            }
        });

        // Education Chart
        const educationCtx = document.getElementById('clusterEducationChart').getContext('2d');
        const educationData = {
            labels: [
                @foreach($clusterStats as $clusterId => $stats)
                    'Cluster {{ $clusterId + 1 }}',
                @endforeach
            ],
            datasets: [
                {
                    label: 'S2',
                    data: [
                        @foreach($clusterStats as $stats)
                            {{ $stats['jenjang_pendidikan']['S2'] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'S3',
                    data: [
                        @foreach($clusterStats as $stats)
                            {{ $stats['jenjang_pendidikan']['S3'] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Lainnya',
                    data: [
                        @foreach($clusterStats as $stats)
                            {{ $stats['jenjang_pendidikan']['Lainnya'] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Tidak Lanjut',
                    data: [
                        @foreach($clusterStats as $stats)
                            {{ $stats['jenjang_pendidikan']['Tidak Lanjut'] ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        };

        const educationChart = new Chart(educationCtx, {
            type: 'bar',
            data: educationData,
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
@endif