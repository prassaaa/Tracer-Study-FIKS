@extends('layouts.app')

@section('title', 'Analisis Clustering')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Analisis Clustering</h1>
        <div>
            <a href="{{ route('admin.clustering.export', $hasilClustering->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 mr-2">
                <i class="fas fa-file-export mr-2"></i> Export Data
            </a>
            <a href="{{ route('admin.clustering.show', $hasilClustering->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">
                <i class="fas fa-eye mr-2"></i> Detail
            </a>
            <a href="{{ route('admin.clustering.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $hasilClustering->nama_proses }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600">{{ $hasilClustering->deskripsi }}</p>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <span class="mr-4">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($hasilClustering->waktu_proses)->format('d M Y H:i') }}
                    </span>
                    <span>
                        <i class="fas fa-layers mr-1"></i> {{ $hasilClustering->jumlah_cluster }} cluster
                    </span>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Parameter</p>
                <div class="space-y-1 text-sm">
                    <p>
                        <span class="text-gray-600 font-medium">Program Studi:</span>
                        <span class="text-gray-800">
                            @if(isset($hasilClustering->parameter['program_studi_id']))
                                {{ \App\Models\ProgramStudi::find($hasilClustering->parameter['program_studi_id'])->nama ?? 'Semua Program Studi' }}
                            @else
                                Semua Program Studi
                            @endif
                        </span>
                    </p>
                    <p>
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
                    </p>
                    <p>
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
                    </p>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Rata-rata Waktu Tunggu per Cluster</h2>
            <canvas id="clusterWaitTimeChart" height="300"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Bidang Pekerjaan</h2>
            <canvas id="clusterJobFieldChart" height="300"></canvas>
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
                            Waktu Tunggu Rata-rata
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Karakteristik Utama
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
                                <div class="text-xs text-gray-500">{{ round(($stats['count'] / $clusterGroups->sum('count')) * 100) }}% dari total</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rp {{ number_format($stats['gaji_rata_rata'], 0, ',', '.') }}</div>
                                @php
                                    $avgSalary = array_sum(array_column($clusterStats, 'gaji_rata_rata')) / count($clusterStats);
                                    $pctDiff = $avgSalary > 0 ? ($stats['gaji_rata_rata'] - $avgSalary) / $avgSalary * 100 : 0;
                                @endphp
                                <div class="text-xs {{ $pctDiff >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $pctDiff >= 0 ? '+' : '' }}{{ round($pctDiff, 1) }}% dari rata-rata
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ round($stats['waktu_tunggu_rata_rata'], 1) }} bulan</div>
                                @php
                                    $avgWaitTime = array_sum(array_column($clusterStats, 'waktu_tunggu_rata_rata')) / count($clusterStats);
                                    $wtPctDiff = $avgWaitTime > 0 ? ($stats['waktu_tunggu_rata_rata'] - $avgWaitTime) / $avgWaitTime * 100 : 0;
                                @endphp
                                <div class="text-xs {{ $wtPctDiff <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $wtPctDiff >= 0 ? '+' : '' }}{{ round($wtPctDiff, 1) }}% dari rata-rata
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @php
                                        $characteristics = [];
                                        
                                        // Karakteristik berdasarkan gaji
                                        if ($pctDiff >= 20) {
                                            $characteristics[] = 'Gaji tinggi';
                                        } elseif ($pctDiff <= -20) {
                                            $characteristics[] = 'Gaji rendah';
                                        }
                                        
                                        // Karakteristik berdasarkan waktu tunggu
                                        if ($wtPctDiff >= 20) {
                                            $characteristics[] = 'Waktu tunggu lama';
                                        } elseif ($wtPctDiff <= -20) {
                                            $characteristics[] = 'Waktu tunggu singkat';
                                        }
                                        
                                        // Karakteristik berdasarkan bidang pekerjaan
                                        if (!empty($stats['bidang_pekerjaan'])) {
                                            $topField = array_key_first($stats['bidang_pekerjaan']);
                                            $topCount = reset($stats['bidang_pekerjaan']);
                                            $topPct = $stats['count'] > 0 ? ($topCount / $stats['count'] * 100) : 0;
                                            
                                            if ($topPct >= 50) {
                                                $characteristics[] = "Dominan di $topField";
                                            }
                                        }
                                        
                                        if (empty($characteristics)) {
                                            $characteristics[] = 'Cluster umum';
                                        }
                                    @endphp
                                    
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($characteristics as $characteristic)
                                            <li>{{ $characteristic }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-blue-50 rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-blue-800 mb-4">Interpretasi Hasil</h2>
        <div class="text-blue-700 space-y-4">
            <p>
                <span class="font-medium">Metode Clustering:</span> Single Linkage Clustering adalah metode hierarki yang menggabungkan cluster berdasarkan jarak minimum antar elemen. Metode ini cenderung membentuk cluster yang memanjang (chain-like).
            </p>
            <p>
                <span class="font-medium">Interpretasi:</span> Dari hasil clustering di atas, dapat dilihat bahwa alumni telah dikelompokkan menjadi {{ $hasilClustering->jumlah_cluster }} cluster dengan karakteristik yang berbeda. Cluster dengan gaji rata-rata tinggi cenderung memiliki karakteristik tertentu yang dapat dilihat pada bidang pekerjaan dan waktu tunggu mereka.
            </p>
            <p>
                <span class="font-medium">Rekomendasi:</span> Pihak kampus dapat menggunakan hasil ini untuk menyusun strategi pengembangan kurikulum dan layanan karir berdasarkan karakteristik cluster. Fokus pada penguatan kompetensi yang mendukung lulusan untuk memasuki bidang dengan gaji lebih tinggi dan waktu tunggu lebih singkat.
        </p>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
       // Chart data from PHP
    const chartData = {{{ $chartDataJson }}};
    
       // Cluster Distribution Chart
    const distributionCtx = document.getElementById('clusterDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.distribution,
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
        new Chart(salaryCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Gaji Rata-rata (juta)',
                    data: chartData.gaji,
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
        
        // Wait Time Chart
        const waitTimeCtx = document.getElementById('clusterWaitTimeChart').getContext('2d');
        new Chart(waitTimeCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Waktu Tunggu (bulan)',
                    data: chartData.waktuTunggu,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
                                return value + ' bulan';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return 'Waktu Tunggu: ' + value.toFixed(1) + ' bulan';
                            }
                        }
                    }
                }
            }
        });
        
        // Job Field Chart (Radar chart)
        const jobFieldCtx = document.getElementById('clusterJobFieldChart').getContext('2d');
        new Chart(jobFieldCtx, {
            type: 'radar',
            data: {
                labels: chartData.bidangLabels || ['Pendidikan', 'Kesehatan', 'IT', 'Pemerintahan', 'Swasta'],
                datasets: chartData.bidangData || [{
                    label: 'Cluster 1',
                    data: [65, 59, 90, 81, 56],
                    fill: true,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                elements: {
                    line: {
                        borderWidth: 3
                    }
                },
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0
                    }
                }
            }
        });
    });
</script>
@endpush