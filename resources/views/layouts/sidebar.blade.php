<aside id="sidebar" class="fixed h-screen bg-white shadow-sm md:block rounded-lg overflow-hidden transition-all duration-300" style="width: 250px;">
    <div class="h-16 flex items-center justify-between border-b bg-blue-50 border-blue-100 px-4">
        <h2 class="text-lg font-semibold text-blue-800">
            @if(Auth::user()->role === 'admin')
                <i class="fas fa-shield-alt mr-2 text-blue-600"></i> Panel Admin
            @else
                <i class="fas fa-user-graduate mr-2 text-blue-600"></i> Panel Alumni
            @endif
        </h2>
        <button id="sidebar-close-btn" class="md:hidden text-blue-600 hover:text-blue-800 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="h-[calc(100vh-4rem)] overflow-y-auto">
        <nav class="py-4">
            <div class="px-4 mb-3">
                <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">Menu Utama</p>
            </div>
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                <i class="fas fa-home w-5 mr-2 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-500' }}"></i>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()->role === 'admin')
                <!-- Menu Admin -->
                <div class="px-4 mt-5 mb-3">
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">Kelola Data</p>
                </div>
                <!-- Persetujuan Alumni -->
                <a href="{{ route('admin.alumni-approval.index') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.alumni-approval.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-user-check w-5 mr-2 {{ request()->routeIs('admin.alumni-approval.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Persetujuan Alumni</span>
                    @php
                        $pendingCount = \App\Models\User::where('role', 'alumni')->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>
                <!-- Program Studi -->
                <a href="{{ route('admin.program-studi.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.program-studi.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-graduation-cap w-5 mr-2 {{ request()->routeIs('admin.program-studi.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Program Studi</span>
                </a>
                <!-- Alumni -->
                <a href="{{ route('admin.alumni.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.alumni.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-user-graduate w-5 mr-2 {{ request()->routeIs('admin.alumni.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Alumni</span>
                </a>
                <!-- Survei -->
                <a href="{{ route('admin.survei.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.survei.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-poll w-5 mr-2 {{ request()->routeIs('admin.survei.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Survei</span>
                </a>
                <!-- Menu Analisis Data -->
                <div class="px-4 mt-5 mb-3">
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">Analisis Data</p>
                </div>
                <a href="{{ route('admin.clustering.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.clustering.*') && !request()->routeIs('admin.clustering.dashboard') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-chart-pie w-5 mr-2 {{ request()->routeIs('admin.clustering.*') && !request()->routeIs('admin.clustering.dashboard') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Clustering</span>
                </a>
                <a href="{{ route('admin.clustering.dashboard') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.clustering.dashboard') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-chart-line w-5 mr-2 {{ request()->routeIs('admin.clustering.dashboard') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Dashboard Analisis</span>
                </a>
            @else
                <!-- Menu Alumni -->
                <div class="px-4 mt-5 mb-3">
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">Data Pribadi</p>
                </div>
                <a href="{{ route('alumni.profile') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('alumni.profile') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-user w-5 mr-2 {{ request()->routeIs('alumni.profile') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Profil</span>
                </a>
                <a href="{{ route('alumni.riwayat-pekerjaan.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('alumni.riwayat-pekerjaan.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-briefcase w-5 mr-2 {{ request()->routeIs('alumni.riwayat-pekerjaan.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Riwayat Pekerjaan</span>
                </a>
                <a href="{{ route('alumni.pendidikan.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('alumni.pendidikan.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-book w-5 mr-2 {{ request()->routeIs('alumni.pendidikan.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Pendidikan Lanjut</span>
                </a>
                
                <div class="px-4 mt-5 mb-3">
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">Survei</p>
                </div>
                <a href="{{ route('alumni.survei.index') }}" class="sidebar-link flex items-center px-4 py-2.5 text-gray-700 hover:bg-blue-50 {{ request()->routeIs('alumni.survei.*') ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }} transition-colors duration-200">
                    <i class="fas fa-poll-h w-5 mr-2 {{ request()->routeIs('alumni.survei.*') ? 'text-white' : 'text-blue-500' }}"></i>
                    <span>Isi Survei</span>
                </a>
            @endif
            
            <div class="mt-6 px-4">
                <div class="bg-blue-50 rounded-md p-3 border border-blue-100">
                    <h3 class="text-sm font-medium text-blue-800 mb-1">Butuh bantuan?</h3>
                    <p class="text-xs text-blue-700">Hubungi admin di:</p>
                    <p class="text-xs text-blue-700 mt-1">admin@unpkediri.ac.id</p>
                </div>
            </div>
        </nav>
    </div>
</aside>

<!-- Toggle Sidebar Button (visible when sidebar is hidden) -->
<button id="sidebar-toggle-btn" class="fixed left-0 top-20 bg-blue-600 text-white p-2 rounded-r-md shadow-md z-40 transition-all duration-300 hover:bg-blue-700 focus:outline-none hidden">
    <i class="fas fa-bars"></i>
</button>