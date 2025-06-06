<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Tracer Study - UNP Kediri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <style>
        .hero-pattern {
            background-color: #0056b3;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath fill-rule='evenodd' d='M11 0l5 20H6l5-20zm42 31a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM0 72h40v4H0v-4zm0-8h31v4H0v-4zm20-16h20v4H20v-4zM0 56h40v4H0v-4zm63-25a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM53 41a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-30 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-28-8a5 5 0 0 0-10 0h10zm10 0a5 5 0 0 1-10 0h10zM56 5a5 5 0 0 0-10 0h10zm10 0a5 5 0 0 1-10 0h10zm-3 46a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM21 0l5 20H16l5-20zm43 64v-4h-4v4h-4v4h4v4h4v-4h4v-4h-4zM36 13h4v4h-4v-4zm4 4h4v4h-4v-4zm-4 4h4v4h-4v-4zm8-8h4v4h-4v-4z'/%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-unp.png') }}" alt="Logo UNP Kediri" class="h-12">
                    <div class="ml-4">
                        <h1 class="text-gray-800 text-xl font-bold">Sistem Tracer Study</h1>
                        <p class="text-gray-600 text-sm">Universitas Nusantara PGRI Kediri</p>
                    </div>
                </div>
                <div class="flex items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-pattern text-white">
        <div class="container mx-auto px-6 py-20">
            <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2 mb-10 md:mb-0">
                    <h2 class="text-4xl font-bold leading-tight mb-4">Sistem Tracer Study UNP Kediri</h2>
                    <p class="text-xl mb-6">Memperkuat hubungan alumni dan memajukan pendidikan melalui data yang bermakna.</p>
                    <div class="flex">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-100">Akses Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-100">Login</a>
                            <a href="{{ route('register') }}" class="ml-4 px-6 py-3 border-2 border-white text-white rounded-md font-semibold hover:bg-blue-700">Register</a>
                        @endauth
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <img src="{{ asset('images/hero-image.png') }}" alt="Tracer Study Illustration" class="mx-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-16">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">Apa itu Tracer Study?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="p-6 bg-blue-50 rounded-lg">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-search text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Penelusuran Alumni</h4>
                    <p class="text-gray-600">Melacak perkembangan karir dan kesuksesan alumni setelah lulus dari universitas.</p>
                </div>
                <div class="p-6 bg-blue-50 rounded-lg">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-chart-pie text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Analisis Data</h4>
                    <p class="text-gray-600">Menganalisis data alumni menggunakan metode Single Linkage Clustering untuk mendapatkan wawasan bermakna.</p>
                </div>
                <div class="p-6 bg-blue-50 rounded-lg">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-graduation-cap text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Peningkatan Pendidikan</h4>
                    <p class="text-gray-600">Data tracer study membantu universitas meningkatkan kualitas pendidikan dan relevansi kurikulum.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="py-16 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-around">
                <div class="text-center mb-8 md:mb-0">
                    <div class="text-4xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'alumni')->count() }}</div>
                    <div class="text-gray-600 mt-2">Alumni Terdaftar</div>
                </div>
                <div class="text-center mb-8 md:mb-0">
                    <div class="text-4xl font-bold text-blue-600">{{ \App\Models\ProgramStudi::count() }}</div>
                    <div class="text-gray-600 mt-2">Program Studi</div>
                </div>
                <div class="text-center mb-8 md:mb-0">
                    <div class="text-4xl font-bold text-blue-600">{{ \App\Models\RiwayatPekerjaan::count() }}</div>
                    <div class="text-gray-600 mt-2">Riwayat Pekerjaan</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600">{{ \App\Models\Survei::where('aktif', true)->count() }}</div>
                    <div class="text-gray-600 mt-2">Survei Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">Testimoni Alumni</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <div class="p-6 bg-gray-50 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <img src="https://via.placeholder.com/60" alt="Alumni Photo" class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h5 class="text-lg font-semibold text-gray-800">Budi Santoso</h5>
                                <p class="text-sm text-gray-600">Alumni Keperawatan 2020</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"Sistem tracer study ini sangat membantu saya tetap terhubung dengan kampus dan sesama alumni. Data yang dikumpulkan juga bermanfaat untuk pengembangan karir."</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <img src="https://via.placeholder.com/60" alt="Alumni Photo" class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h5 class="text-lg font-semibold text-gray-800">Sari Indah</h5>
                                <p class="text-sm text-gray-600">Alumni Pendidikan Biologi 2019</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"Setelah mengisi survei tracer study, saya mendapatkan banyak insight tentang peluang karir di bidang saya. Analisis data yang disajikan sangat informatif."</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4">
                            <img src="https://via.placeholder.com/60" alt="Alumni Photo" class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h5 class="text-lg font-semibold text-gray-800">Ahmad Fauzi</h5>
                                <p class="text-sm text-gray-600">Alumni Peternakan 2021</p>
                            </div>
                        </div>
                        <p class="text-gray-600">"Platform yang user-friendly dan mudah diakses. Saya senang bisa berkontribusi data untuk pengembangan kualitas pendidikan di almamater saya."</p>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Call to Action -->
        <div class="py-16 bg-blue-600 text-white">
            <div class="container mx-auto px-6 text-center">
                <h3 class="text-3xl font-bold mb-6">Bergabunglah dengan Komunitas Alumni UNP Kediri</h3>
                <p class="text-xl mb-8">Daftarkan diri Anda sekarang dan berkontribusilah dalam pengembangan pendidikan di almamater kita.</p>
                <div class="flex justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-100">Akses Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-100">Daftar Sekarang</a>
                    @endauth
                </div>
            </div>
        </div>
    
        <!-- Footer -->
        <footer class="py-10 bg-gray-800 text-white">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="mb-6 md:mb-0">
                        <div class="flex items-center">
                            <img src="{{ asset('images/logo-unp.png') }}" alt="Logo UNP Kediri" class="h-10">
                            <div class="ml-4">
                                <h4 class="text-lg font-bold">Sistem Tracer Study</h4>
                                <p class="text-sm text-gray-400">Universitas Nusantara PGRI Kediri</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-400">Jl. K.H. Ahmad Dahlan No.76, Mojoroto, Kec. Mojoroto, Kota Kediri, Jawa Timur 64112</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                        <div>
                            <h5 class="text-lg font-semibold mb-4">Tentang Kami</h5>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white">Profil Universitas</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Visi & Misi</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Fasilitas</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold mb-4">Program Studi</h5>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white">Kebidanan</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Keperawatan</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Peternakan</a></li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold mb-4">Kontak</h5>
                            <ul class="space-y-2">
                                <li class="flex items-center"><i class="fas fa-phone-alt mr-2 text-gray-400"></i> <span class="text-gray-400">(0354) 771576</span></li>
                                <li class="flex items-center"><i class="fas fa-envelope mr-2 text-gray-400"></i> <span class="text-gray-400">info@unpkediri.ac.id</span></li>
                                <li class="flex items-center"><i class="fas fa-globe mr-2 text-gray-400"></i> <span class="text-gray-400">www.unpkediri.ac.id</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} Sistem Tracer Study - Universitas Nusantara PGRI Kediri</p>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>