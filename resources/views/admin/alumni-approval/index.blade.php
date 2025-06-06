@extends('layouts.app')

@section('title', 'Persetujuan Alumni')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Persetujuan Alumni</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.alumni-approval.approved') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-check-circle mr-2"></i> Alumni Disetujui
            </a>
            <a href="{{ route('admin.alumni-approval.rejected') }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                <i class="fas fa-times-circle mr-2"></i> Alumni Ditolak
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Daftar Permintaan Pendaftaran Alumni</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Pendaftaran
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
                    @foreach ($pendingUsers as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.alumni-approval.approve', $user->id) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    <button type="button" class="px-3 py-1 bg-green-100 text-green-600 rounded-md hover:bg-green-200">
                                        <i class="fas fa-check mr-1"></i> Terima
                                    </button>
                                </a>
                                
                                <button type="button" class="px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 delete-btn" data-id="{{ $user->id }}">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                                
                                <form id="reject-form-{{ $user->id }}" action="{{ route('admin.alumni-approval.reject', $user->id) }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($pendingUsers->count() == 0)
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada permintaan pendaftaran alumni yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $pendingUsers->links() }}
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
                
                if (confirm('Apakah Anda yakin ingin menolak pendaftaran alumni ini?')) {
                    document.getElementById(`reject-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush