<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Tracer Study FIKS UNP Kediri')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-unp.png') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Style -->
    <style>
        :root {
            --primary-color: #0067b3;
            --primary-hover: #004d86;
            --primary-light: #e6f3ff;
            --secondary-color: #cce5ff;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 250px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23104e8b' fill-opacity='0.03'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        .card-shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .content-card {
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .sidebar-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        .sidebar-link.active i {
            color: white !important;
        }
        .alert-success {
            background-color: #e6f7e6;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .alert-danger {
            background-color: #fae7e7;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .alert-warning {
            background-color: #fff3e0;
            border-left: 4px solid #ffc107;
            color: #856404;
        }
        .alert-info {
            background-color: #e0f7fa;
            border-left: 4px solid #17a2b8;
            color: #0c5460;
        }
        /* Sidebar styling */
        #sidebar {
            z-index: 40;
            width: var(--sidebar-width);
            left: 0;
            transition: left 0.3s ease;
        }
        #sidebar.hidden {
            left: calc(-1 * var(--sidebar-width));
        }
        #main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }
        #main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            #sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.show {
                left: 0;
            }
            #main-content {
                margin-left: 0;
                width: 100%;
            }
            #sidebar-toggle-btn {
                display: flex !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

    @include('layouts.navigation')

    <div class="flex">
        @auth
            @include('layouts.sidebar')

            <main id="main-content" class="flex-1 p-4 lg:p-6 bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>

                    <button id="sidebar-toggle-desktop" class="hidden md:flex items-center justify-center text-blue-600 hover:text-blue-800 focus:outline-none bg-white rounded-md p-2 shadow-sm">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert-success p-3 mb-4 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-danger p-3 mb-4 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert-warning p-3 mb-4 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                            <p>{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert-info p-3 mb-4 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <p>{{ session('info') }}</p>
                        </div>
                    </div>
                @endif

                <div class="content-card p-4 lg:p-6">
                    @yield('content')
                </div>
            </main>
        @else
            <main class="flex-1 p-4 lg:p-6 bg-gray-50">
                <div class="content-card p-4 lg:p-6">
                    @yield('content')
                </div>
            </main>
        @endauth
    </div>

    <footer class="bg-white text-center p-4 mt-6 border-t">
        <div class="container mx-auto">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Sistem Tracer Study FIKS - Universitas Nusantara PGRI (UNP) Kediri</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
            const sidebarToggleDesktop = document.getElementById('sidebar-toggle-desktop');
            const sidebarCloseBtn = document.getElementById('sidebar-close-btn');

            // Function to toggle sidebar on mobile
            function toggleSidebarMobile() {
                sidebar.classList.toggle('show');
                if (sidebar.classList.contains('show')) {
                    sidebarToggleBtn.style.left = '250px';
                } else {
                    sidebarToggleBtn.style.left = '0';
                }
            }

            // Function to toggle sidebar on desktop
            function toggleSidebarDesktop() {
                sidebar.classList.toggle('hidden');
                mainContent.classList.toggle('expanded');
            }

            // Mobile toggle button
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', toggleSidebarMobile);
            }

            // Desktop toggle button
            if (sidebarToggleDesktop) {
                sidebarToggleDesktop.addEventListener('click', toggleSidebarDesktop);
            }

            // Close button in sidebar
            if (sidebarCloseBtn) {
                sidebarCloseBtn.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarToggleBtn.style.left = '0';
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.innerWidth < 768;
                if (isMobile && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(event.target) && event.target !== sidebarToggleBtn) {
                        sidebar.classList.remove('show');
                        sidebarToggleBtn.style.left = '0';
                    }
                }
            });

            // Adjust on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show');
                    sidebarToggleBtn.style.left = '0';
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
