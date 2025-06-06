@extends('layouts.app')

@section('title', 'Detail Program Studi')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Program Studi</h1>
        <div>
            <a href="{{ route('admin.program-studi.edit', $programStudi->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.program-studi.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Program Studi</h2>
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Nama Program Studi</td>
                        <td class="py-2 text-gray-800">{{ $programStudi->nama }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Fakultas</td>
                        <td class="py-2 text-gray-800">{{ $programStudi->fakultas }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Kode</td>
                        <td class="py-2 text-gray-800">{{ $programStudi->kode }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-600 font-medium">Jumlah Alumni</td>
                        <td class="py-2 text-gray-800">{{ $programStudi->alumniProfiles->count() }} orang</td>
                    </tr>
                </table>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Deskripsi</h2>
                <p class="text-gray-700">{{ $programStudi->deskripsi ?: 'Tidak ada deskripsi' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Alumni</h2>
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
                            Jenis Kelamin
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tahun Masuk
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
                    @foreach ($programStudi->alumniProfiles as $alumni)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $alumni->nim }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $alumni->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $alumni->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $alumni->jenis_kelamin }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $alumni->tahun_masuk }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $alumni->tahun_lulus }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.alumni.show', $alumni->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if($programStudi->alumniProfiles->count() == 0)
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data alumni.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection