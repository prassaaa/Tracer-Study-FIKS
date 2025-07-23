@extends('layouts.app')

@section('title', 'Daftar Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Alumni</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.alumni.export.csv') }}" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                <i class="fas fa-file-csv mr-2"></i> Export CSV
            </a>
            <a href="{{ route('admin.alumni-approval.index') }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                <i class="fas fa-user-clock mr-2"></i> Persetujuan Alumni
            </a>
            <a href="{{ route('admin.alumni.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Tambah Alumni
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-md">
            <p class="font-medium">{{ session('info') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <form action="{{ route('admin.alumni.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Cari berdasarkan nama atau NIM..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ request('search') }}">
                </div>
                <div class="w-full md:w-1/4">
                    <select name="program_studi" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Program Studi</option>
                        @foreach(\App\Models\ProgramStudi::orderBy('nama')->get() as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('program_studi') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/6">
                    <select name="tahun_lulus" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Tahun Lulus</option>
                        @foreach(range(date('Y'), 2010) as $year)
                            <option value="{{ $year }}" {{ request('tahun_lulus') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($alumni as $alumnus)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $alumnus->nim }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($alumnus->foto)
                                            <img src="{{ asset('storage/alumni/' . $alumnus->foto) }}" alt="{{ $alumnus->nama_lengkap }}" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $alumnus->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $alumnus->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $alumnus->programStudi->nama ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $alumnus->programStudi->fakultas ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $alumnus->tahun_lulus }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $pekerjaan = $alumnus->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                                    $pendidikan = $alumnus->pendidikanLanjut()->where('sedang_berjalan', true)->first();
                                @endphp

                                @if($pekerjaan)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Bekerja
                                    </span>
                                @endif

                                @if($pendidikan)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 {{ $pekerjaan ? 'ml-1' : '' }}">
                                        Studi Lanjut
                                    </span>
                                @endif

                                @if(!$pekerjaan && !$pendidikan)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Belum Update
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.alumni.show', $alumnus->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.alumni.edit', $alumnus->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900 delete-btn" data-id="{{ $alumnus->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $alumnus->id }}" action="{{ route('admin.alumni.destroy', $alumnus->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($alumni->count() == 0)
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data alumni.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            {{ $alumni->withQueryString()->links() }}
        </div>
    </div>

    <!-- Menampilkan daftar user approved tanpa profil alumni -->
    @php
        $usersWithoutProfile = \App\Models\User::where('role', 'alumni')
            ->where('status', 'approved')
            ->whereNotIn('id', function($query) {
                $query->select('user_id')->from('alumni_profiles');
            })
            ->get();
    @endphp

    @if(count($usersWithoutProfile) > 0)
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Alumni Tanpa Profil</h2>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-4 border-b bg-yellow-50">
                    <p class="text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Berikut adalah daftar alumni yang sudah disetujui tetapi belum memiliki profil. Klik tombol "Buat Profil" untuk membuat profil alumni.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    NIM
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Daftar
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($usersWithoutProfile as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->nim }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.alumni.create-profile', $user->id) }}" class="px-3 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200">
                                            <i class="fas fa-user-plus mr-1"></i> Buat Profil
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                if (confirm('Apakah Anda yakin ingin menghapus data alumni ini?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush
