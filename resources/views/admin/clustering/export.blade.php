@extends('layouts.app')

@section('title', 'Export Hasil Clustering')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Export Hasil Clustering</h1>
        <div>
            <button onclick="printReport()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">
                <i class="fas fa-print mr-2"></i> Print
            </button>
            <a href="{{ route('admin.clustering.show', $hasilClustering->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6" id="report-content">
        <div class="border-b pb-6 mb-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Laporan Hasil Clustering Alumni</h2>
                <h3 class="text-xl font-semibold text-gray-700">Universitas Nusantara PGRI Kediri</h3>
                <p class="text-gray-600">{{ $hasilClustering->nama_proses }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Informasi Clustering</h4>
                    <table class="w-full">
                        <tr>
                            <td class="py-2 text-gray-600 font-medium">Metode</td>
                            <td class="py-2 text-gray-800">Single Linkage Clustering</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600 font-medium">Jumlah Cluster</td>
                            <td class="py-2 text-gray-800">{{ $hasilClustering->jumlah_cluster }} cluster</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600 font-medium">Waktu Proses</td>
                            <td class="py-2 text-gray-800">{{ \Carbon\Carbon::parse($hasilClustering->waktu_proses)->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Parameter</h4>
                    <table class="w-full">
                        <tr>
                            <td class="py-2 text-gray-600 font-medium">Program Studi</td>
                            <td class="py-2 text-gray-800">
                                @if(isset($hasilClustering->parameter['program_studi_id']))
                                    {{ \App\Models\ProgramStudi::find($hasilClustering->parameter['program_studi_id'])->nama ?? 'Semua Program Studi' }}
                                @else
                                    Semua Program Studi
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600 font-medium">Tahun Lulus</td>
                            <td class="py-2 text-gray-800">
                                @if(isset($hasilClustering->parameter['tahun_lulus_awal']) && isset($hasilClustering->parameter['tahun_lulus_akhir']))
                                    {{ $hasilClustering->parameter['tahun_lulus_awal'] }} - {{ $hasilClustering->parameter['tahun_lulus_akhir'] }}
                                @elseif(isset($hasilClustering->parameter['tahun_lulus_awal']))
                                    ≥ {{ $hasilClustering->parameter['tahun_lulus_awal'] }}
                                @elseif(isset($hasilClustering->parameter['tahun_lulus_akhir']))
                                    ≤ {{ $hasilClustering->parameter['tahun_lulus_akhir'] }}
                                @else
                                    Semua Tahun
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600 font-medium">Atribut</td>
                            <td class="py-2 text-gray-800">
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
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Statistik Cluster</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                Cluster
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                Jumlah Alumni
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                Gaji Rata-rata
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats as $stat)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap border">
                                    <div class="font-medium text-gray-900">Cluster {{ $stat['cluster'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border">
                                    <div class="text-sm text-gray-900">{{ $stat['jumlah_anggota'] }} alumni</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border">
                                    <div class="text-sm text-gray-900">{{ $stat['gaji_rata_rata'] }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Daftar Alumni per Cluster</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach($headers as $header)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $row)
                            <tr>
                                @foreach($headers as $key => $header)
                                    <td class="px-6 py-4 whitespace-nowrap border">
                                        <div class="text-sm text-gray-900">{{ $row[strtolower(str_replace(' ', '_', $header))] ?? '-' }}</div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-sm text-gray-500 mt-8 pt-6 border-t">
            <p>Laporan ini dibuat otomatis oleh Sistem Tracer Study FIKS UNP Kediri pada {{ now()->format('d M Y H:i') }}.</p>
            <p>Metode Single Linkage Clustering digunakan untuk mengelompokkan alumni berdasarkan kesamaan karakteristik.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function printReport() {
        const printContents = document.getElementById('report-content').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = `
            <div style="padding: 20px;">
                ${printContents}
            </div>
        `;

        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
@endpush
