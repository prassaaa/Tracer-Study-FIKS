@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">Selamat datang di Sistem Tracer Study UNP Kediri</p>
    </div>

    @if(Auth::user()->role === 'admin')
        <!-- Admin Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="p-6 bg-white rounded-lg border border-gray-100 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Alumni</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalAlumni }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">User</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalUser }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Program Studi</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalProdi }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Riwayat Pekerjaan</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $riwayatPekerjaan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Alumni Approval Cards -->
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pendaftaran Alumni</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-medium text-yellow-800">Menunggu</h4>
                            <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                {{ \App\Models\User::where('role', 'alumni')->where('status', 'pending')->count() }}
                            </span>
                        </div>
                        <p class="text-yellow-600 text-sm">Alumni yang belum disetujui</p>
                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.alumni-approval.index') }}" class="text-sm text-yellow-700 font-medium hover:underline">
                                <i class="fas fa-arrow-right mr-1"></i> Lihat
                            </a>
                        </div>
                    </div>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-medium text-green-800">Disetujui</h4>
                            <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                {{ \App\Models\User::where('role', 'alumni')->where('status', 'approved')->count() }}
                            </span>
                        </div>
                        <p class="text-green-600 text-sm">Alumni yang sudah disetujui</p>
                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.alumni-approval.approved') }}" class="text-sm text-green-700 font-medium hover:underline">
                                <i class="fas fa-arrow-right mr-1"></i> Lihat
                            </a>
                        </div>
                    </div>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-medium text-red-800">Ditolak</h4>
                            <span class="bg-red-200 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                {{ \App\Models\User::where('role', 'alumni')->where('status', 'rejected')->count() }}
                            </span>
                        </div>
                        <p class="text-red-600 text-sm">Alumni yang ditolak</p>
                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.alumni-approval.rejected') }}" class="text-sm text-red-700 font-medium hover:underline">
                                <i class="fas fa-arrow-right mr-1"></i> Lihat
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h-60">
                    <canvas id="alumniApprovalChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Alumni per Program Studi</h3>
                <canvas id="alumniPerProdiChart" height="300"></canvas>
            </div>
            
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Alumni Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Lulus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(\App\Models\AlumniProfile::with('programStudi')->latest()->take(5)->get() as $alumni)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $alumni->nama_lengkap }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alumni->nim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alumni->programStudi->nama ?? 'Belum diisi' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alumni->tahun_lulus ?? 'Belum diisi' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.alumni.show', $alumni->id) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if(\App\Models\AlumniProfile::count() == 0)
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data alumni.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Alumni Dashboard -->
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/3 bg-blue-600 p-6 text-white">
                    <div class="flex items-center mb-4">
                        <div class="mr-4">
                            @if($profile && $profile->foto)
                                <img src="{{ asset('storage/alumni/' . $profile->foto) }}" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover">
                            @else
                                <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center">
                                    <i class="fas fa-user text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $profile ? $profile->nama_lengkap : Auth::user()->name }}</h2>
                            <p class="text-blue-200">{{ Auth::user()->nim ?? 'NIM belum diisi' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p><i class="fas fa-graduation-cap mr-2"></i> {{ $profile && $profile->programStudi ? $profile->programStudi->nama : 'Program studi belum diisi' }}</p>
                        <p><i class="fas fa-calendar-alt mr-2"></i> Tahun Lulus: {{ $profile ? $profile->tahun_lulus : '-' }}</p>
                    </div>
                </div>
                <div class="w-full md:w-2/3 p-6">
                    @if(Auth::user()->status === 'pending')
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Akun Anda masih menunggu persetujuan admin. Anda tidak dapat mengakses fitur lengkap sampai akun Anda disetujui.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->status === 'rejected')
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Pendaftaran Anda telah ditolak. Silakan hubungi administrator untuk informasi lebih lanjut.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Profil</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Kelengkapan Profil</h4>
                            @php
                                $isProfileComplete = $profile && 
                                                    $profile->jenis_kelamin && 
                                                    $profile->tanggal_lahir && 
                                                    $profile->program_studi_id && 
                                                    $profile->tahun_masuk && 
                                                    $profile->tahun_lulus;
                            @endphp
                            @if(!$isProfileComplete)
                                <div class="flex items-center justify-between">
                                    <span class="text-red-600"><i class="fas fa-times-circle mr-2"></i> Belum lengkap</span>
                                    <a href="{{ route('alumni.profile') }}" class="text-blue-600 hover:underline">Lengkapi</a>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-green-600"><i class="fas fa-check-circle mr-2"></i> Lengkap</span>
                                    <a href="{{ route('alumni.profile') }}" class="text-blue-600 hover:underline">Edit</a>
                                </div>
                            @endif
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Riwayat Pekerjaan</h4>
                            @if(!$profile || count($pekerjaan ?? []) == 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-red-600"><i class="fas fa-times-circle mr-2"></i> Belum diisi</span>
                                    <a href="{{ route('alumni.riwayat-pekerjaan.create') }}" class="text-blue-600 hover:underline">Tambah</a>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-green-600"><i class="fas fa-check-circle mr-2"></i> {{ count($pekerjaan) }} pekerjaan</span>
                                    <a href="{{ route('alumni.riwayat-pekerjaan.index') }}" class="text-blue-600 hover:underline">Lihat</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(Auth::user()->status === 'approved')
                        <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4">Survei Aktif</h3>
                        <div class="space-y-4">
                            @foreach(\App\Models\Survei::where('aktif', true)->where('tanggal_mulai', '<=', now())->where('tanggal_selesai', '>=', now())->get() as $survei)
                                <div class="bg-blue-50 rounded-lg p-4 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-700">{{ $survei->judul }}</h4>
                                        <p class="text-sm text-gray-600">Batas waktu: {{ \Carbon\Carbon::parse($survei->tanggal_selesai)->format('d M Y') }}</p>
                                    </div>
                                    <a href="{{ route('alumni.survei.show', $survei->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Isi Survei</a>
                                </div>
                            @endforeach
                            
                            @if(\App\Models\Survei::where('aktif', true)->where('tanggal_mulai', '<=', now())->where('tanggal_selesai', '>=', now())->count() == 0)
                                <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-600">
                                    <p>Tidak ada survei aktif saat ini.</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if(Auth::user()->status === 'approved' && $profile && count($pekerjaan ?? []) > 0)
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pekerjaan Terkini</h3>
                @php
                    $pekerjaanTerkini = $pekerjaan->where('pekerjaan_saat_ini', true)->first() ?? $pekerjaan->sortByDesc('tanggal_mulai')->first();
                @endphp
                @if($pekerjaanTerkini)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xl font-semibold text-gray-800">{{ $pekerjaanTerkini->posisi }}</p>
                            <p class="text-gray-600">{{ $pekerjaanTerkini->nama_perusahaan }}</p>
                            <p class="text-gray-500 text-sm">{{ $pekerjaanTerkini->lokasi }}</p>
                            <p class="text-gray-500 text-sm mt-2">
                                <i class="far fa-calendar-alt mr-1"></i> 
                                {{ \Carbon\Carbon::parse($pekerjaanTerkini->tanggal_mulai)->format('M Y') }} - 
                                {{ $pekerjaanTerkini->tanggal_selesai ? \Carbon\Carbon::parse($pekerjaanTerkini->tanggal_selesai)->format('M Y') : 'Sekarang' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600"><strong>Bidang:</strong> {{ $pekerjaanTerkini->bidang_pekerjaan }}</p>
                            @if($pekerjaanTerkini->gaji)
                                <p class="text-gray-600"><strong>Gaji:</strong> Rp {{ number_format($pekerjaanTerkini->gaji, 0, ',', '.') }}</p>
                            @endif
                            @if($pekerjaanTerkini->deskripsi_pekerjaan)
                                <p class="text-gray-600 mt-2"><strong>Deskripsi:</strong> {{ \Illuminate\Support\Str::limit($pekerjaanTerkini->deskripsi_pekerjaan, 100) }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Data pekerjaan tidak ditemukan.</p>
                @endif
            </div>
        @endif
    @endif
@endsection

@if(Auth::user()->role === 'admin')
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alumni per program studi chart
            const ctxProdi = document.getElementById('alumniPerProdiChart').getContext('2d');
            
            const dataProdi = {
                labels: [
                    @foreach($alumniPerProdi as $prodi)
                        '{{ $prodi->nama }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: [
                        @foreach($alumniPerProdi as $prodi)
                            {{ $prodi->alumni_profiles_count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            };
            
            const prodiChart = new Chart(ctxProdi, {
                type: 'bar',
                data: dataProdi,
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Alumni approval status chart
            const ctxApproval = document.getElementById('alumniApprovalChart').getContext('2d');
            
            const dataApproval = {
                labels: ['Menunggu', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ \App\Models\User::where('role', 'alumni')->where('status', 'pending')->count() }},
                        {{ \App\Models\User::where('role', 'alumni')->where('status', 'approved')->count() }},
                        {{ \App\Models\User::where('role', 'alumni')->where('status', 'rejected')->count() }}
                    ],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            };
            
            const approvalChart = new Chart(ctxApproval, {
                type: 'doughnut',
                data: dataApproval,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endif