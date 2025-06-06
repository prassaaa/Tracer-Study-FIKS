<nav class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-unp.png') }}" alt="Logo UNP Kediri" class="h-10 w-auto">
                    <div class="ml-3">
                        <span class="text-xl font-semibold text-gray-800">Tracer Study</span>
                        <span class="hidden md:block text-xs text-gray-500">UNP Kediri</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center">
                @auth
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="text-gray-700 truncate max-w-[150px]">{{ Auth::user()->name }}</span>
                        <div class="relative dropdown-container">
                            <button id="userDropdownButton" class="dropdown-trigger flex items-center text-gray-700 focus:outline-none">
                                @if(Auth::user()->alumniProfile && Auth::user()->alumniProfile->foto)
                                    <img src="{{ asset('storage/alumni/' . Auth::user()->alumniProfile->foto) }}" alt="Foto Profil" class="h-9 w-9 rounded-full object-cover border border-blue-200">
                                @else
                                    <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium border border-blue-200">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <svg class="ml-1 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="userDropdownMenu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden border border-gray-100">
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user text-gray-500 w-5 mr-2"></i>
                                        <span>Profil</span>
                                    </a>
                                @else
                                    <a href="{{ route('alumni.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user text-gray-500 w-5 mr-2"></i>
                                        <span>Profil</span>
                                    </a>
                                @endif
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt text-gray-500 w-5 mr-2"></i>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="md:hidden">
                        <button class="mobile-menu-button p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium px-3 py-2 transition-colors duration-200">Login</a>
                    <a href="{{ route('register') }}" class="ml-4 px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200 shadow-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden hidden mobile-menu border-t absolute w-full bg-white">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @auth
                <div class="flex items-center px-3 py-2">
                    @if(Auth::user()->alumniProfile && Auth::user()->alumniProfile->foto)
                        <img src="{{ asset('storage/alumni/' . Auth::user()->alumniProfile->foto) }}" alt="Foto Profil" class="h-9 w-9 rounded-full object-cover border border-blue-200">
                    @else
                        <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium border border-blue-200">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="ml-3 font-medium text-gray-800 truncate">{{ Auth::user()->name }}</span>
                </div>
                <div class="border-t border-gray-100 my-2"></div>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-home text-gray-500 w-5 mr-2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-user text-gray-500 w-5 mr-2"></i>
                        <span>Profil</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-home text-gray-500 w-5 mr-2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('alumni.profile') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-user text-gray-500 w-5 mr-2"></i>
                        <span>Profil</span>
                    </a>
                @endif
                <div class="border-t border-gray-100 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt text-gray-500 w-5 mr-2"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                    <i class="fas fa-sign-in-alt text-gray-500 w-5 mr-2"></i>
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                    <i class="fas fa-user-plus text-gray-500 w-5 mr-2"></i>
                    <span>Daftar</span>
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Spacer to prevent content from hiding behind the fixed navbar -->
<div class="h-16"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User dropdown menu handling
        const userDropdownButton = document.getElementById('userDropdownButton');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        let dropdownOpen = false;
        
        if (userDropdownButton && userDropdownMenu) {
            // Toggle dropdown on click
            userDropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                if (dropdownOpen) {
                    userDropdownMenu.classList.add('hidden');
                } else {
                    userDropdownMenu.classList.remove('hidden');
                }
                dropdownOpen = !dropdownOpen;
            });
            
            // Add small delay before closing dropdown to make it easier to move cursor to menu
            userDropdownButton.addEventListener('mouseover', function() {
                userDropdownMenu.classList.remove('hidden');
                dropdownOpen = true;
            });
            
            // Prevent immediate closing when moving from button to menu
            userDropdownMenu.addEventListener('mouseover', function() {
                userDropdownMenu.classList.remove('hidden');
                dropdownOpen = true;
            });
            
            // Add a small delay before hiding when mouse leaves menu
            let timeoutId;
            document.addEventListener('mouseover', function(e) {
                const isOverDropdown = userDropdownMenu.contains(e.target);
                const isOverTrigger = userDropdownButton.contains(e.target);
                
                if (!isOverDropdown && !isOverTrigger && dropdownOpen) {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        userDropdownMenu.classList.add('hidden');
                        dropdownOpen = false;
                    }, 3000); // 300ms delay makes it easier to reach dropdown
                }
            });
        }
        
        // Mobile menu handling
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.classList.contains('hidden')) {
                    if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        }
    });
</script>
@endpush